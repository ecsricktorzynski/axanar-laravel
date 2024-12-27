<?php

namespace Ares\Http\Controllers;

use Ares\Database\Db;
use Ares\Models\Campaigns;
use Ares\Models\Packages;
use Ares\Models\SKUItems;
use Ares\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AdminController extends AuthenticatedController
{
    /** @inheritdoc */
    public function __construct()
    {
        parent::__construct();

        if (!session('admin')) {
            redirect()->route('dashboard');
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function members()
    {
        return view('admin.members', ['campaignString' => getPlatformsAndCampaigns()]);
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function formResetLink()
    {
        return view('admin.resetlink');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function campaigns()
    {
        $html = null;
        $campaigns = Campaigns::where('active', 1)->get()->toArray();
        $skuTotals = [];
        foreach ($campaigns ?? [] as $index => $campaign) {
            $campaigns[$index]['collapseID'] = 'c' . $campaign['campaign_id'];
            $campaigns[$index]['elements'] = $els = Campaigns::getElements((int)$campaign['campaign_id']);
            $lastName = null;

            if (empty($els)) {
                unset($campaigns[$index]['elements']);
                continue;
            }
            $campaign_id = $campaign['campaign_id'];
            // $campaignItems[$campaign_id] = Campaigns::initSkuItems($campaign_id);
            foreach ($els ?? [] as $elIndex => $element) {
                $packageId = (int)$element['package_id'];
                
                // get cost to display instead of name
                $packageCost = $element['cost'];


                // get numToShip
                $numToShip = Campaigns::getNumPackagesToShip($packageId);
                $numShipped = Campaigns::getNumPackagesShipped($packageId);

                $packageName = $element['packageName'] ?? 'Unknown Package';
                if ($packageName !== $lastName) {
                    
                    
                    // print "packageId = " . $package_id . "<br>";
                    // print "campaign_id = " . $campaign['campaign_id'];
                    
                    $packageItems = SKUItems::getPackageItems($packageId);
                    if (!empty($packageItems)) {
                        $campaigns[$index]['elements'][$elIndex]['packageItems'] = $packageItems;

                        foreach ($packageItems as $packageItem) {
                            $sku_id = $packageItem['name'];
                            $numItems = $numToShip[0];
                            
                            if ($sku_id && $numItems) {
                                if(empty($skuTotals[$campaign_id][$sku_id])){
                                  $skuTotals[$campaign_id][$sku_id] = $numItems;
                                } else {
                                  $skuTotals[$campaign_id][$sku_id] += $numItems;
                                }
                            }
                            
                            
                          // $skuId = (int)$packageItem['sku_id'];
                          // $campaigns[$index]['elements'][$elIndex]['itemTotal'] += $numToShip[0];
                        }
                        $campaigns[$index]['elements'][$elIndex]['numToShip'] = $numToShip[0];
                        $campaigns[$index]['elements'][$elIndex]['numShipped'] = $numShipped[0];
                        $campaigns[$index]['elements'][$elIndex]['packageId'] = $packageId;
                        $campaigns[$index]['elements'][$elIndex]['packageCost'] = $packageCost;
                    
                        $campaigns[$index]['elements'][$elIndex]['campaignID'] = $campaign['campaign_id'];
                        
                    }
                                
                    
                    $lastName = $packageName;
                }
            }
        }

       // return view('admin.campaigns', ['campaigns' => $campaigns, 'skuTotals' => $skuTotals]);
       return view('admin.campaigns', ['campaigns' => $campaigns]);
    }

    public function tier(Request $request, int $packageID = null)
    {
        $item = Packages::find($packageID)->toArray();
        $orders = getListToShipForTier($packageID);
        $filled = getListShippedForTier($packageID);

        if (!empty($request->get('all'))) {
            foreach ($orders as $order) {
                $userID = (int)$order['user_id'];
                if (!checkListForShipped($userID, $filled)) {
                    toggleShippedPackage(1, $userID, $packageID);
                }
            }
        }

        if (empty($item)) {
            return back()->with('status', 'Invalid Package ID');
        }

        return view(
            'admin.tier',
            [
                'packageID' => $packageID,
                'item' => $item,
                'orders' => $orders,
                'filled' => $filled
            ]
        );
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tiers()
    {
        $campaigns = Campaigns::where('active', 1)->orderBy('end_date')->get()->toArray();
        if (empty($campaigns) || !is_array($campaigns)) {
            $campaigns = [];
        }

        foreach ($campaigns as $key => $campaign) {
            $campaignID = (int)$campaign['campaign_id'];
            $items = Packages::getByCampaignID($campaignID);

            foreach ($items as $itemKey => $item) {
                $packageID = (int)$item['package_id'];
                $unshipped = getListToShipForTier($packageID);
                $unshippedCount = $unshipped === false || !is_array($unshipped) ? 'N/A' : count($unshipped);
                $shipped = getListShippedForTier($packageID);
                $shippedCount = $shipped === false || !is_array($shipped) ? 'N/A' : count($shipped);
                $items[$itemKey]['shipped'] = $shipped;
                $items[$itemKey]['shippedCount'] = number_format($shippedCount, 0);
                $items[$itemKey]['unshipped'] = $unshipped;
                $items[$itemKey]['unshippedCount'] = number_format($unshippedCount, 0);
            }

            $campaigns[$key]['items'] = $items;
        }

        return view('admin.tiers', ['campaigns' => $campaigns]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function fulfillmentDashboard()
    {
        return view('admin.fulfillment');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int|null                 $skuID
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function items(Request $request, int $skuID = null)
    {
        $item = getSkuItem($skuID);
        $orders = getListToShipForItem($skuID);
        $filled = getListShippedForItem($skuID);

        if (!empty($request->get('all'))) {
            foreach ($orders as $order) {
                $userID = $order['user_id'];
                if (!checkListForShipped($userID, $filled)) {
                    toggleShipped(1, $userID, $skuID);
                }
            }
        }

        if (empty($item)) {
            return back()->with('status', 'Invalid SKU ID');
        }

        return view('admin.items', ['skuID' => $skuID, 'item' => $item, 'orders' => $orders, 'filled' => $filled]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiMembers(Request $request)
    {
        $requestData = $request->request->all();
        $columns = [
            0 => 'user_id',
            1 => 'full_name',
            2 => 'email'
        ];

        $sql = null;
        $totalRows = $totalFiltered = User::where('deleted', 0)->count();

        $conditions = $binds = [];

        if (!empty($requestData['platform']) || !empty($requestData['campaign'])) {
            $conditions[] = 'deleted = 0';

            if (!empty($requestData['platform'])) {
                $conditions[] = 'c.provider = ?';
                $binds[] = $requestData['platform'];
            }

            if (!empty($requestData['campaign'])) {
                $conditions[] = 'c.campaign_id = ?';
                $binds[] = $requestData['campaign'];
            }

            if (!empty($requestData['search']['value'])) {
                $likeString = '%' . $requestData['search']['value'] . '%';
                $conditions[] = '(u.user_id LIKE ? OR email LIKE ? OR full_name LIKE ?)';
                //  3 binds
                $binds[] = $likeString;
                $binds[] = $likeString;
                $binds[] = $likeString;
            }
        } else {
            $conditions[] = 'deleted = 0';

            if (!empty($requestData['search']['value'])) {
                $likeString = '%' . $requestData['search']['value'] . '%';
                $conditions[] = '(u.user_id LIKE ? 
                                OR u.email LIKE ?
                                OR u.full_name LIKE ?
                                OR u.last_name LIKE ? 
                                OR a.firstName LIKE ?
                                OR a.lastName LIKE ?
                                OR a.altName LIKE ?
                                OR CONCAT(a.firstName, \' \', a.lastName) LIKE ?
                                OR CONCAT(u.first_name, \' \', u.last_name) LIKE ?)';
                //  9 binds
                $binds[] = $likeString;
                $binds[] = $likeString;
                $binds[] = $likeString;
                $binds[] = $likeString;
                $binds[] = $likeString;
                $binds[] = $likeString;
                $binds[] = $likeString;
                $binds[] = $likeString;
                $binds[] = $likeString;
            }

            $where = implode(' AND ', $conditions);
            $orderBy = $columns[$requestData['order'][0]['column']] ?? 1;
            $direction = $requestData['order'][0]['dir'] ?? 'ASC';
            $sql = <<<MYSQL
SELECT 
    u.*,
    a.firstName,
    a.lastName,
    a.altName,
    (select sum(ucp.user_amount) from UserCampaignPackages ucp where ucp.user_id = u.user_id) as user_amount
FROM Users u
LEFT JOIN Addresses a ON u.user_id = a.user_id
WHERE {$where}
ORDER BY {$orderBy} {$direction}
LIMIT {$requestData['start']}, {$requestData['length']}
MYSQL;
        }

        if (empty($sql)) {
            $where = implode(' AND ', $conditions);
            $orderBy = $columns[$requestData['order'][0]['column']] ?? 1;
            $direction = $requestData['order'][0]['dir'] ?? 'ASC';
            $sql = <<<MYSQL
SELECT DISTINCT u.*, sum(p.user_amount) as user_amount
FROM Users u
JOIN UserCampaignPackages p ON u.user_id = p.user_id
JOIN Campaigns c ON p.campaign_id = c.campaign_id 
WHERE {$where}
ORDER BY {$orderBy} {$direction}
LIMIT {$requestData['start']}, {$requestData['length']}
MYSQL;
        }

        $rows = Db::select($sql, $binds);

        //  Set totalFiltered if there was a search
        if (!empty($requestData['search']['value'])) {
            $totalFiltered = count($rows);
        }

        $response = [
            'draw' => (int)$requestData['draw'],
            'recordsTotal' => (int)$totalRows,
            'recordsFiltered' => (int)$totalFiltered,
            'data' => $this->buildDataTablesArray($rows)
        ];

        return JsonResponse::create($response);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiMemberDetails(Request $request)
    {
        if (empty($userId = $request->post('userId'))) {
            return JsonResponse::create([]);
        }

        $user = getUserRecord($userId);
        $account = getUserAccountInfo($userId);
        if (empty($user)) {
            return JsonResponse::create([]);
        }

        $user['full_name'] = $this->getDisplayName($user);
        $user['address'] = 'None on Record';

        if (!empty($account)) {
            $user['address'] = $account['address_1'] ?? null;

            if (!empty($account['address_2'])) {
                $user['address'] .= '<br />' . $account['address_2'];
            }

            if (!empty($account['city'])) {
                $user['address'] .= '<br />' . $account['city'];
            }
            if (!empty($account['state'])) {
                $user['address'] .= ', ' . $account['state'];
            }
            if (!empty($account['zip'])) {
                $user['address'] .= ' ' . $account['zip'];
            }
            if (!empty($account['country'])) {
                $user['address'] .= '<br />' . $account['country'];
            }
        }

        if (empty($user['last_login'])) {
            $user['last_login'] = 'Never Logged In';
        }

        $campaigns = Campaigns::getByUserId(1, $userId);
        $user['campaigns'] = [];

        if (empty($campaigns)) {
            return JsonResponse::create($user);
        }

        foreach ($campaigns as $campaign) {
            $p = getUserDonationsForCampaign((int)$campaign['campaign_id'], $userId);
            $campaign['packages'] = [];

            foreach ($p ?? [] as $package) {
                $package['sku'] = getItemsForPackageByUser($package['package_id'], $userId);

                foreach ($package['sku'] ?? [] as $skuKey => $sku) {
                    $icon = null;
                    $available = $sku['available'] ?? 0;

                    switch ((int)$sku['type']) {
                        case 1:
                            $icon = 'download';
                            if (!$available) {
                                $icon = 'question-sign';
                            }
                            break;
                        case 4:
                            $icon = 'globe';
                            break;
                        case 3:
                            $icon = 'globe';
                            break;
                        case 2:
                            $icon = 'question-sign';
                            if ($available === 1) {
                                $icon = 'exclamation-sign';
                                if ((int)$sku['shipped'] === 1) {
                                    $icon = 'ok';
                                }
                            }
                    }

                    $package['sku'][$skuKey]['icon'] = $icon;
                    $package['sku'][$skuKey]['available'] = $available;
                }
                $campaign['packages'][] = $package;
            }
            $user['campaigns'][] = $campaign;
        }

        $resetPass = $user['resetPass'] ?? null;

        if (empty($resetPass)) {
            $resetPass = 'None Set';
        } elseif ($resetPass === 'used') {
            $resetPass = 'Already Used';
        } else {
            $resetPass .= ' use <a href="/password/email">this link</a>';
        }
        $user['resetPass'] = $resetPass;

        return JsonResponse::create($user);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiSetShipping(Request $request)
    {
        $skuID = $request->get('skuId');
        $userID = $request->get('userId');
        $action = $request->get('action');
        if (empty($skuID) || empty($userID) || $action === null) {
            return JsonResponse::create(['success' => false, 'error' => 'Invalid request'], 400);
        }
        if (!toggleShipped($action, $userID, $skuID)) {
            return JsonResponse::create(['success' => false, 'error' => 'Database error'], 500);
        }
        return JsonResponse::create(['success' => true]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int                      $packageID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiSetShippingForPackage(Request $request, int $packageID)
    {
        $userID = $request->get('userId');
        $action = $request->get('action');
        if (empty($packageID) || empty($userID) || $action === null) {
            return JsonResponse::create(['success' => false, 'error' => 'Invalid request'], 400);
        }
        if (!toggleShippedPackage($action, $userID, $packageID)) {
            return JsonResponse::create(['success' => false, 'error' => 'Database error'], 500);
        }
        return JsonResponse::create(['success' => true]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int                      $packageID
     */
    public function getShippingListForPackage(Request $request, int $packageID)
    {
        return $this->getShippingList($request, null, $packageID);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int|null                 $skuID
     * @param int|null                 $packageID
     */
    public function getShippingList(Request $request, ?int $skuID = null, ?int $packageID = null)
    {
        $skuID = $skuID ?? $request->get('skuId');
        $packageID = $packageID ?? $request->get('packageId');
        if (!empty($skuID)) {
            makeFileForShipping($skuID);
        }
        if (!empty($packageID)) {
            makeFileForShippingPackages($packageID);
        }
    }

    /**
     * @param array $results
     *
     * @return array
     */
    protected function buildDataTablesArray($results): array
    {
        $array = [];

        foreach ($results as $index => $row) {
            $amount = $row['user_amount'] === null
                ? 'N/A'
                : number_format($row['user_amount'], 0);
            $array[] = [
                $row['user_id'],
                $this->getDisplayName($row),
                $row['email'],
                $amount,
            ];
        }

        return $array;
    }

    /**
     * @param array $user
     *
     * @return null|string
     */
    protected function getDisplayName(array $user)
    {
        $name = "";

        if (!(strpos($user['full_name'], '@')) AND !empty($user['full_name'])) {
            $name = $user['full_name'];
        }
        if (!empty($user['firstName'])) {
            $name = $user['firstName'] . ' ' . ($user['lastName'] ?? null);
        }

        if ($name == "") {
            $compiledNames = getCompiledNames($user['user_id']);
            
            if (!empty($compiledNames[0]['cn_full_name'])) {
                $name = $compiledNames[0]['cn_full_name'];
            }
            if (!empty($compiledNames[0]['cn_fullname'])) {
                $name = $compiledNames[0]['cn_fullname'];
            }
            
            // $name = $user['user_id'];
        }
        
        /* 
        if (!empty($user['altName']) && $user['altName'] !== $name) {
            $name .= ' (listed as ' . $user['altName'] . ')';
        }
        */
        return $name;
    }

    /**
     * @param int $userID
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function impersonateUser($userID)
    {
        if (empty($userID)) {
            return redirect()->back();
        }

        /** @var \Ares\User $user */
        $user = User::find($userID);
        $user->impersonate($user);

        return redirect('/');
    }

    public function impersonateUserLeave()
    {
        /** @var \Ares\User $user */
        Auth::user()->leaveImpersonation();
        return redirect('logout');
    }


    public function getNewPassResetUrl($userID){
        $user = User::find($userID);
        $token=str_random(60);
        \DB::table('password_resets')->where('email',$user->email)->delete();
        \DB::table('password_resets')->insert([
            'email' => $$user->email,
            'token' => \Hash::make($token), //change 60 to any length you want
            'created_at' => \Carbon\Carbon::now()
        ]);
        return url('reset',$token);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function createResetLink(Request $request)
    {
        /*
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if( ! $validator->fails() )
        {
        */
            if( $user = User::where('email', $request->input('email') )->first() )
            {
                $token = str_random(64);
                /*
                DB::table(config('auth.passwords.users.table'))->insert([
                    'email' => $user->email, 
                    'token' => $token
                ]);
                */
                \DB::table('password_resets')->where('email',$user->email)->delete();
                \DB::table('password_resets')->insert([
                    'email' => $user->email,
                    'token' => \Hash::make($token), //change 60 to any length you want
                    'created_at' => \Carbon\Carbon::now()
                ]);
                
                $reset_link = "https://aresdigital.axanar.com/password/reset/" . $token . "?email=" . $user->email;

                return redirect()->back()->with('status', $reset_link);
            }
        // }
        
        return redirect()->back()->withErrors(['email' => trans(Password::INVALID_USER)]);
    }
}

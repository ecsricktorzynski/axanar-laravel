<?php
  
namespace Ares\Http\Controllers\Auth;
  
use Ares\Http\Controllers\Controller;
use Ares\Database\Db;
use Ares\Models\Campaigns;
use Ares\Models\Packages;
use Ares\Models\SKUItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Ares\Models\User;
use Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
  
class AuthController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index(): View
    {
        return view('auth.login');
    }  



      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registration(): View
    {
        return view('auth.registration');
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postLogin(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            #  changing from dashboard to campaign
            return redirect()->intended('campaign')
                        ->withSuccess('You have Successfully loggedin');
        }
  
        return redirect("login")->withError('Oppes! You have entered invalid credentials');
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postRegistration(Request $request): RedirectResponse
    {  
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
           
        $data = $request->all();
        $user = $this->create($data);
            
        Auth::login($user); 

        return redirect("dashboard")->withSuccess('Great! You have Successfully loggedin');
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function dashboard()
    {

        $html = null;
        $campaigns = Campaigns::where('active', 1)->get()->toArray();
        $inactiveCampaigns = Campaigns::where('active', 0)->get()->toArray();
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
        if(Auth::check()){
            // return view('admin.campaigns', ['campaigns' => $campaigns]);
            return view('dashboard', ['campaigns' => $campaigns, 'inactiveCampaigns' => $inactiveCampaigns]);
        }
  
        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function campaign()
    {
        if(Auth::check()){
            return view('campaign');
        }
  
        return redirect("login")->withSuccess('Opps! You do not have access');
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout(): RedirectResponse
    {
        Session::flush();
        Auth::logout();
  
        return Redirect('index');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function faq()
    {
        if(Auth::check()){
            return view('faq');
        }
  
        return redirect("login")->withSuccess('Opps! You do not have access');
    }
}

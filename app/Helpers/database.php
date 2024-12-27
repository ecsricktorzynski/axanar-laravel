<?php

use Ares\Database\Db;
use Ares\Models\Alerts;
use Ares\Models\AlertsSeen;
use Ares\Models\Campaigns;
use Ares\Models\Packages;
use Ares\Models\SKUItems;
use Ares\Models\UserCampaignPackages;
use Ares\Models\UserSKUItems;
use Ares\Services\AccountService;
use Ares\User;
use Ares\Utility\Data;
use Ares\Utility\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

if (!function_exists('db')) {
    require_once __DIR__ . '/email_lib.php';

    /**
     * Get a database connection
     *
     * @return bool|\mysqli
     */
    function db()
    {
        static $connection = null;
        if (empty($connection)) {
            $connection = Db::connect();
        }
        return $connection;
    }

    function encrypt_decrypt($action, $string)
    {
        $output = false;

        $encrypt_method = 'AES-256-CBC';
        $secret_key = '991J7P2PO80ntT65ObVPWi95Xe61i16H';
        $secret_iv = 'AxanarWins';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        switch ($action) {
            case 'encrypt':
                $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
                $output = base64_encode($output);
                break;
            case 'decrypt':
                $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
                break;
        }

        return $output;
    }

    function randomString($len = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $len; $i++) {
            $randomString .= $characters[rand(0, strlen($characters))];
        }
        return $randomString;
    }

    function setResetLink($email)
    {
        $u = getUserByEmail($email);
        if (!is_null($u)) {
            $seed = RandomString();
            $qSeed = Db::quote($seed);
            $sql = "update Users set resetPass = $qSeed where user_id = " . $u['user_id'];
            Db::query($sql);
            return $seed;
        }
        return null;
    }

    function checkEmail($userName)
    {
        $name = cleanEmail($userName);
        $sql = "SELECT * FROM Users WHERE email=$name and deleted=0";
        $rows = Db::select($sql);
        $status = ['status' => false, 'msg' => 'unknownEmail'];
        session(['userId' => null]);

        if (count($rows) > 0) {
            $u = $rows[0];
            $status['status'] = true;
            $status['userId'] = $u['user_id'];
            $status['msg'] = null;
        }

        return $status;
    }

    /**
     * @return bool
     */
    function isAdmin()
    {
        return AccountService::isAdmin();
    }

    /**
     * @param int  $campaignId
     * @param bool $userId
     *
     * @return array|bool
     */
    function getUserDonationsForCampaign($campaignId, $userId = false)
    {
        return Campaigns::getUserDonations($campaignId, $userId);
    }

    function getItemsForPackage($packageId)
    {
        return SKUItems::getPackageItems($packageId);
    }

    function getItemsForPackageByUser($packageId, $userId = 0)
    {
        if ($userId == 0) {
            $userId = getUserId();
        }
        $sql =
            "select s.sku_id, s.sku_id as skuId, s.*, i.*, u.* from SKUItems s join PackageSkuItems i on s.sku_id = i.sku_id left join UserSKUItems u on i.sku_id = u.sku_id where (u.user_id = $userId or u.user_id is null) and i.package_id = $packageId";
        return Db::select($sql);
    }

    function userHasStoreAccess()
    {
        return session('hasStoreAccess');
    }

    function getUsersFirstFleetEligible($userId = 0)
    {
        $uid = "";
        if ($userId > 0) {
            $uid = "where user_id = $userId";
        }

        $sql =
            "select user_id, sum(user_amount) totalDonated from UserCampaignPackages $uid group by 1 having sum(user_amount) >= 35";
        $results = Db::select($sql);
        if ($results && count($results) > 0) {
            return $results;
        }
        return false;
    }

    function getShippedStatusForUserItemFull($userId, $skuId)
    {
        $sql = "select * from UserSKUItems where user_id = $userId and sku_id = $skuId";
        $r = getDataSet($sql);
        if ($r) {
            $r = $r[0];
            if ($r['shipped'] == 1) {
                return $r;
            } else {
                return null;
            }
        }
        return null;
    }

    function getShippedStatusForUserItem($userId, $skuId)
    {
        $sql = "select * from UserSKUItems where user_id = $userId and sku_id = $skuId";
        $r = getDataSet($sql);
        if ($r) {
            $r = $r[0];
            return $r['shipped'] == 1;
        }
        return false;
    }

    /**
     * @param int $itemId
     *
     * @return array
     */
    function getSkuItem($itemId)
    {
        return SKUItems::find($itemId)->toArray();
    }

    function getSkuItems()
    {
        $sql = "SELECT * FROM SKUItems ORDER BY sku_id";
        return Db::select($sql);
    }

    function doLogin($userName, $userPassword)
    {
        $name = cleanEmail($userName);
        $sql = "SELECT * FROM Users WHERE email=$name and deleted=0";
        $rows = Db::select($sql);
        $status = [];
        $status['status'] = false;
        $status['msg'] = "unknownEmail";
        session(['userId' => null]);

        if (count($rows) > 0) {
            $passHash = md5($userPassword);
            $u = $rows[0];
            if ($u['password'] == $passHash) {
                $status['status'] = true;
                $status['userId'] = $u['user_id'];
                $status['msg'] = null;
                session('userId', $u['user_id']);
                session('admin', $u['admin']);
                $sql = "update Users set last_login = now() where user_id = " . $u['user_id'];
                addToAudit("loginOk", $u['user_id'], "user logged in");
                Db::query($sql);
            } else {
                $status['msg'] = "incorrectPassword";
                addToAudit("loginBadPassword", 0, "invalid password");
            }
        }
        return $status;
    }

    /**
     * @param null $campaignId
     *
     * @return array|bool|mixed
     */
    function getCampaigns($campaignId = null)
    {
        if (!empty($campaignId)) {
            return Campaigns::find($campaignId)->toArray();
        }

        return Campaigns::orderBy('end_date')->get()->toArray();
    }

    /**
     * @param int $campaignId
     *
     * @return array
     */
    function getPackagesForCampaign($campaignId)
    {
        return Packages::getByCampaignID((int)$campaignId);
    }

    function getElementsForCampaign($campaignId)
    {
        $sql =
            "select p.name packageName, a.name skuName, p.*, a.* from Packages p left join (select pi.package_id, i.* from PackageSkuItems pi join SKUItems i on pi.sku_id = i.sku_id) as a on p.package_id = a.package_id where p.campaign_id = $campaignId;";
        return Db::select($sql);
    }

    /**
     * @param int      $active
     * @param int|null $userId
     *
     * @return array
     */
    function getCampaignsForUser(int $active = 1)
    {
        return Campaigns::getByUserId($active);
    }

    function getCampaign($campaignId)
    {
        $sql = "SELECT * FROM Campaigns where campaign_id = $campaignId;";
        $result = Db::select($sql);
        return $result;
    }

    function checkListForShipped($user_id, $filledSet)
    {
        if (!is_array($filledSet)) {
            return false;
        }
        $user_id = (int)$user_id;
        foreach ($filledSet as $i) {
            if ((int)$i['user_id'] === $user_id) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param int      $skuID
     * @param int|null $userID
     *
     * @return array|bool|mixed
     */
    function getListToShipForItem($skuID, $userID = null)
    {
        $sql = "call getListToShipForItem($skuID);";
        return @getDataSet($sql) ?: [];
    }

    function getListShippedForItem($skuID)
    {
        $sql = "call getListShippedForItem($skuID);";
        $rows = getDataSet($sql) ?: [];
        return $rows;
    }

    function getListToShipForTier($packageID)
    {
        $sql = <<<SQL
CALL getListToShipForTier(?)
SQL;
        $rows = \Illuminate\Support\Facades\DB::select($sql, [$packageID]);
        return Data::resultsToArray($rows);
    }

    function getListShippedForTier($packageID)
    {
        $sql = <<<SQL
CALL getListShippedForTier(?)
SQL;
        $rows = \Illuminate\Support\Facades\DB::select($sql, [$packageID]);
        return Data::resultsToArray($rows);
    }

    /**
     * @param $action
     * @param $userID
     * @param $packageID
     *
     * @return bool
     */
    function toggleShippedPackage($action, $userID, $packageID)
    {
        if (UserCampaignPackages::setShipped((int)$action, (int)$userID, (int)$packageID)) {
            return 'ok';
        }

        Log::error('Failed to mark package shipped', [
            'action' => $action,
            'userID' => $userID,
            'packageID' => $packageID
        ]);
        return 'ok';
    }

    /**
     * @param $action
     * @param $userID
     * @param $skuID
     *
     * @return string
     */
    function toggleShipped($action, $userID, $skuID)
    {
        if (UserSKUItems::setShipped((int)$action, (int)$userID, (int)$skuID)) {
            return 'ok';
        }

        Log::error('Failed to mark item shipped', ['action' => $action, 'userID' => $userID, 'skuID' => $skuID]);
        return 'ok';
    }

    function utf8_encode_deep(&$input)
    {
        if (is_string($input)) {
            $input = utf8_encode($input);
        } else {
            if (is_array($input)) {
                foreach ($input as &$value) {
                    utf8_encode_deep($value);
                }

                unset($value);
            } else {
                if (is_object($input)) {
                    $vars = array_keys(get_object_vars($input));

                    foreach ($vars as $var) {
                        utf8_encode_deep($input->$var);
                    }
                }
            }
        }
    }

    function makeFileForShipping($skuId)
    {
        $item = [getSkuItem($skuId)];
        if (is_array($item) && count($item) == 1) {
            $item = $item[0];
            $name = $item['name'];
            $fileName = "shipping_$name.csv";
            $columns =
                [
                    "UserId",
                    "Name",
                    "Email",
                    "Address",
                    "Address2",
                    "City",
                    "Country",
                    "State",
                    "Zip",
                    "Gender",
                    "ShirtSize",
                    "# of Units",
                ];

            $sql = <<<SQL
CALL getListToShipForItem({$skuId})
SQL;
            GenerateCSVFile($fileName, $sql, $columns);
        }
        die();
    }

    function makeFileForShippingPackages($packageID)
    {
        $item = Packages::find($packageID)->toArray();
        if (empty($item)) {
            die();
        }

        $name = $item['name'];
        $fileName = "shipping_$name.csv";
        $columns = [
            'UserId',
            'Name',
            'Email',
            'Address',
            'Address2',
            'City',
            'Country',
            'State',
            'Zip',
            'Gender',
            'ShirtSize',
            '# of Units',
        ];

        $sql = <<<SQL
CALL getListToShipForTier({$packageID})
SQL;

        $shipped = getListShippedForTier($packageID);
        GenerateCSVFile($fileName, $sql, $columns, $shipped);

        die();
    }

    function GenerateCSVFile($fileName, $sql, $columnNameArray, $shipped = null)
    {
        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        // output the column headings
        fputcsv($output, $columnNameArray);

        $rows = getDataSet($sql);

        // loop over the rows, outputting them
        if (is_array($rows)) {
            foreach ($rows as $row) {
                $userId = $row['user_id'];
                $has = checkListForShipped($userId, $shipped);

                foreach ($row as $k => $v) {
                    error_log($k . ":" . $v);
                }

                if ($shipped === null) {
                    fputcsv($output, $row);
                } else {
                    if (!$has) {
                        fputcsv($output, $row);
                    }
                }
            }
        }
    }

    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function sendPasswordLink($email)
    {
        $resetKey = generateRandomString(5);
        $email = cleanEmail($email);
        $sql = 'UPDATE Users SET resetPass = \'' . $resetKey . '\' WHERE email = \'' . $email . '\'';
        Db::query($sql);
        /** @noinspection PhpUnusedLocalVariableInspection */
        $link = 'http://www.axanar.com/account-changepassword.php?key=' . $resetKey;
        /** @noinspection PhpUnusedLocalVariableInspection */
        $subject = 'Ares Digital: Password Reset';
    }

    function getPlatformsAndCampaigns(bool $active = true)
    {
        $active = $active ? 'active = 1' : 'active = 0';

        /** @noinspection SqlResolve */
        $sql = <<<MYSQL
SELECT campaign_id,
       name,
       CASE
         WHEN provider = 1
           THEN "KICKSTARTER"
         ELSE "INDIEGOGO"
         END AS platform
FROM dbDigital.Campaigns
WHERE {$active}
ORDER BY 3, 2;
MYSQL;

        $results = Db::select($sql);
        $options = null;

        if ($results && count($results) > 0) {
            foreach ($results as $result) {
                $options .= '<option value="' .
                            $result['campaign_id'] .
                            '">' .
                            $result['name'] .
                            '</option>';
            }
        }

        return $options;
    }

    function addSkuToPackage($packageId, $skuId)
    {
        if (is_numeric($packageId) && is_numeric($skuId)) {
            $sql = <<<MYSQL
INSERT into PackageSkuItems 
  (package_id, sku_id) 
VALUES 
  (${packageId}, ${skuId})
MYSQL;
            return Db::query($sql);
        }

        return false;
    }

    function removePackageSkuItemById($packageSkuItemId)
    {
        if (is_numeric($packageSkuItemId)) {
            $sql = "delete from PackageSkuItems where id=$packageSkuItemId;";
            Db::query($sql);
        }
    }

    function getPackageById($packageId)
    {
        $sql = "select * from Packages where package_id = $packageId;";
        return getDataSet($sql);
    }

    function addCampaignPackageToUser($userId, $packageId, $amt)
    {
        $sql = "select * from Packages where package_id = $packageId;";
        $r = Db::select($sql);
        $campaignId = $r[0]['campaign_id'];
        addToAudit("userAddPackage", $userId, "adding package $packageId to $userId");
        $sql = <<<MYSQL
INSERT INTO UserCampaignPackages
  (campaign_id, user_id, package_id, user_amount) 
VALUES
  (${campaignId}, ${userId}, ${packageId}, ${amt})
MYSQL;
        Db::query($sql);
    }

    function removeCampaignPackageFromUser($userId, $packageId)
    {
        addToAudit("userRemovePackage", $userId, "removing package $packageId from $userId");
        $sql = "delete from UserCampaignPackages where user_id = $userId and package_id = $packageId";
        Db::query($sql);
    }

    function changePasswordWithKey($email, $key, $np)
    {
        $email = cleanEmail($email);
        $sql = "select * from Users where email = $email and deleted=0";
        $r = Db::select($sql);
        if (!is_array($r) || count($r) < 1) {
            return "email";
        }
        $u = $r[0];
        if ($u['resetPass'] != $key) {
            return "key";
        }
        $userId = $u['user_id'];
        $new = Db::quote(md5($np));
        $sql = "Update Users set password = $new, resetPass='used' where user_id = $userId";
        Db::query($sql);
        return "ok";
    }

    function updateEmailAddress($userId, $email)
    {
        $email = cleanEmail($email);
        $sql = "update Users set email = $email where user_id = $userId";
        addToAudit("userEmailUpdate", $userId, "user email updated to $email");
        Db::query($sql);
    }

    function changeAssociationsToNewId($oldUserId, $newUserId)
    {
        $sql = "update UserSKUItems set user_id = $newUserId where user_id = $oldUserId";
        Db::query($sql);
        $sql = "update UserCampaignPackages set user_id = $newUserId where user_id = $oldUserId";
        Db::query($sql);
    }

    function changeEmailById($id, $orig, $new)
    {
        if (confirmLoggedIn(false)) {
            $user = getUserRecord($id);
            if ($user && is_array($user) && count($user) > 0) {
                $user = $user[0];
                $oldUserId = $user['user_id'];
                $email = $user['email'];
                if ($orig != $email) {
                    echo "bad";
                    return "badOrig";
                }
                $u = getUserByEmail($new);
                if ($u) {
                    $newUserId = $u['user_id'];

                    addToAudit("userMerge", $newUserId, "user merged from $oldUserId to $newUserId");
                    changeAssociationsToNewId($oldUserId, $newUserId);
                    deleteUser($oldUserId);
                    return "merged";
                } else {
                    updateEmailAddress($oldUserId, $new);
                    return "ok";
                }
            }
        }
        return "notLoggedIn";
    }

    function changeEmail($orig, $new)
    {
        if (confirmLoggedIn(false)) {

            $user = getUserRecord();
            if ($user && is_array($user) && count($user) > 0) {
                $user = $user[0];
                $oldUserId = $user['user_id'];
                $email = $user['email'];
                if ($orig != $email) {
                    echo "bad";
                    return "badOrig";
                }
                $u = getUserByEmail($new);
                if ($u) {
                    $newUserId = $u['user_id'];

                    addToAudit("userMerge", $newUserId, "user merged from $oldUserId to $newUserId");
                    changeAssociationsToNewId($oldUserId, $newUserId);
                    deleteUser($oldUserId);
                    return "merged";
                } else {
                    updateEmailAddress($oldUserId, $new);
                    return "ok";
                }
            }
        }
        return "notLoggedIn";
    }

    /**
     * @param string      $action
     * @param string|int  $new_val
     * @param string|null $notes
     * @param array       $metadata
     *
     * @return array|bool|int|\mysqli_result
     */
    function addToAudit(string $action, $new_val, string $notes = null, array $metadata = [])
    {
        return User::audit($action, $new_val, $notes, $metadata);
    }

    function changePasswords($orig, $new)
    {
        Password::change($orig, $new);
    }

    function getUserId()
    {
        return Auth::id() ?? null;
    }

    function createNewUser($first, $last, $email)
    {
        addToAudit("createUser", 0, "new user created: $email");
        $full = "$first $last";
        $first = Db::quote($first);
        $last = Db::quote($last);
        $email = cleanEmail($email);
        $full = Db::quote($full);
        $sql =
            "insert into Users (first_name, last_name, email, full_name, password,need_update) values ($first, $last, $email, $full, 'hash',1);";
        Db::query($sql);
    }

    function getUserByEmail($email)
    {
        $email = cleanEmail($email);
        $sql = "select * from Users where email = $email and deleted=0";
        $results = Db::select($sql);
        if ($results && count($results) > 0) {
            return $results[0];
        }
        return null;
    }

    function deleteUser($userId)
    {
        addToAudit("userDelete", 0, "deleting user $userId");
        $sql = "update Users set deleted=1 where user_id = $userId";
        Db::query($sql);
    }

    function getCurrentUser()
    {
        $userId = getUserId();
        $results = Db::select('SELECT * FROM Users WHERE user_id = ? AND deleted = ?', [$userId, 0]);
        if ($results && count($results) > 0) {
            return (array)$results[0];
        }
        return null;
    }

    function getAlertById($id)
    {
        $alert = Alerts::find($id);
        if ($alert) {
            echo "
        <div class='sidebar-block'>
            <div class='alert alert-" .
                 $alert['type'] .
                 "'><a href='#' id='close' class='close' data-dismiss='alert' aria-label='close' onclick='clearAlert(\"" .
                 $alert['alert_id'] .
                 "\");'>&times;</a>
                <h4><i class='" .
                 $alert['icon'] .
                 "'></i> &nbsp;<strong>" .
                 $alert['title'] .
                 "</strong></h4>
                <p style='text-align:justify;'>" .
                 $alert['body'] .
                 "</p>
            </div>
        </div>";
        }
    }

    function checkForUserAlert($userId, $alertId)
    {
        $count = AlertsSeen::where(['alert_id' => $alertId, 'user_id' => $userId])->count();
        return $count > 0;
    }

    function getMemcacheEntry($key)
    {
        $key = md5($key);
        $mem = new Memcached();
        $mem->addServer("127.0.0.1", 11211);
        $result = $mem->get($key);
        error_log("trying to fetch $key");
        if ($result) {
            error_log("found one");
        }
        return $result;
    }

    function setMemcacheEntry($key, $value)
    {
        $key = md5($key);
        $mem = new Memcached();
        $mem->addServer("127.0.0.1", 11211);
        $mem->set($key, $value, 600);
        error_log("setting memcache for $key");
    }

    function getDataSet($sql, array $bindings = [])
    {
        /*
        $key = "mc_$sql";
        $mc = getMemcacheEntry($key);
        if ($mc)
        {
            error_log($mc[0]);
            return $mc[0];
        }
        */

        $r = Db::select($sql, $bindings);

        if ($r === false) {
            error_log('SQL error: [' . Db::error() . ']');
            //echo Db::error();
            //echo $sql;
            //var_dump($r);
        }
        if ($r && is_array($r) && count($r) > 0) {
            //  setMemcacheEntry($key, $r);
            return $r;
        }
        return false;
    }

    function getPhysicalSkuItems()
    {
        $sql = "SELECT * FROM SKUItems WHERE type=2 ORDER BY name;";
        return getDataSet($sql);
    }

    function getDonorsForCampaign($campaignId)
    {
        $sql =
            "select distinct u.user_id, u.full_name, a.firstName, a.lastName,a.altName from Users u  left join Addresses a on u.user_id = a.user_id join UserCampaignPackages p on u.user_id = p.user_id where p.campaign_id = $campaignId";
        $r = Db::select($sql);
        if ($r && is_array($r) && count($r) > 0) {
            return $r;
        }
        return null;
    }

    function updateUserField($userID, $field, $val)
    {
        $user = User::find($userID);
        if (empty($user)) {
            return false;
        }

        $user->{$field} = $val;
        return $user->save();
    }

    function updateUserAlert($userId, $alertId)
    {
        if ((int)$alertId === 1) {
            updateUserField(getUserId(), 'need_update', 0);
            AlertsSeen::where(['user_id' => $userId, 'alert_id' => $alertId])->delete();
            return;
        }

        if (!checkForUserAlert($userId, $alertId)) {
            $model = new AlertsSeen();
            $model->user_id = $userId;
            $model->alert_id = $alertId;
            $model->save();
        }
    }

    function showSystemMessages()
    {
        $u = getCurrentUser();
        $userId = Auth::id();
        $i = getUserAccountInfo();
        $needs = false;
        if ($i == null || count($i) == 0) {
            $needs = true;
        }

        if ($u['need_update'] == 1 || $needs) {
            getAlertById(1);
        }

        $alerts = Alerts::where('alert_id', '>', 1)->where('active', 1)->get();
        if (empty($alerts)) {
            return;
        }

        foreach ($alerts as $alert) {
            $aId = $alert['alert_id'];
            if (!checkForUserAlert($userId, $aId)) {
                getAlertById($aId);
            }
        }
    }

    function showMessages($msg = null, $title = null, $type = null, $useSystem = true)
    {
        if (!empty($msg)) {
            switch ($type) {
                case 'error':
                    $t = 'danger';
                    $icon = 'fa fa-medkit';
                    break;
                case 'alert':
                    $t = 'info';
                    $icon = 'fa fa-warning';
                    break;
                case 'info':
                default:
                    $t = 'info';
                    $icon = 'fa fa-info-circle';
                    break;
            }

            echo <<<HTML
<div class="sidebar-block">
    <div class="alert alert-$t">
        <h4><i class="${icon}"></i>&nbsp;<strong>$title</strong></h4>
        <p>$msg</p>
    </div>
</div>
HTML;
        }

        if (confirmLoggedIn(false)) {
            if ($useSystem) {
                showSystemMessages();
            }
        }
    }

    function getUserRecord($userId = null)
    {
        return User::where('deleted', 0)->find($userId ?? Auth::id())->toArray();
    }

    function getCompiledNames($userId = null) {

        // Db::enableQueryLog();

        $results = Db::select(
            'SELECT cn.cn_full_name, cn.cn_fullname , u.email
            FROM compiledNames cn
            JOIN Users u on cn.user_id = u.user_id
            WHERE u.user_id = ?
            LIMIT 0,1',
                [$userId ?? Auth::id()]
        );
        // print "results = $results";
        // dd(Db::getQueryLog());
        return $results;
    }

    function getUserAccountInfo($userId = null)
    {
        
        $rows = Db::select(
            'SELECT a.*, u.email 
               FROM Addresses a 
               JOIN Users u ON a.user_id = u.user_id  
               WHERE a.user_id = ?',
            [$userId ?? Auth::id()]
        );
        /* 
        $rows = Db::select(
            'SELECT a.*, cn.cn_full_name, cn.cn_fullname , u.email
            FROM Addresses a
            JOIN Users u on a.user_id = u.user_id
            JOIN compiledNames cn on cn.user_id = u.user_id
            WHERE a.user_id = ?',
                [$userId ?? Auth::id()]
        );
        */
        

        if (count($rows) === 1) {
            $rows = reset($rows);
        }
        return $rows;
    }

    
    function confirmLoggedIn($stopPage = true)
    {
        if (!Auth::check()) {
            if ($stopPage) {
                redirect()->route('login');
            }
            return false;
        }

        return true;
    }

    function confirmAdmin($stopPage = true)
    {
        if (!isAdmin()) {
            if ($stopPage) {
                redirect()->route('login');
            }
            return false;
        }

        return true;
    }

    /**
     * @param string $email
     *
     * @return mixed
     */
    function cleanEmail($email)
    {
        return DB::quote(filter_var(trim($email), FILTER_SANITIZE_EMAIL));
    }
}

<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wwatters
 * Date: 7/5/16
 * Time: 10:27 PM
 * To change this template use File | Settings | File Templates.
 */
require_once __DIR__ . '/bootstrap.php';
$u = array();
$u['status'] = 'error';
if (isAdmin())
{
    if ($_REQUEST && is_array($_REQUEST)
        && array_key_exists("action",$_REQUEST)
        && array_key_exists("userId",$_REQUEST)
        && array_key_exists("packageId",$_REQUEST)
        && array_key_exists("amt",$_REQUEST))
    {
        $action = $_REQUEST['action'];
        $userId = $_REQUEST['userId'];
        $packageId = $_REQUEST['packageId'];
        $amt = $_REQUEST['amt'];

        if (is_numeric($userId) && is_numeric($packageId))
        {
            switch($action)
            {
                case "add":
                    addCampaignPackageToUser($userId,$packageId,$amt);
                    break;
                case "remove":
                    removeCampaignPackageFromUser($userId,$packageId);
                    break;
            }
            $u['status']='ok';
        }
    }
}
echo json_encode($u);

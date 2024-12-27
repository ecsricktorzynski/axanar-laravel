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
        && (array_key_exists("packageSkuItemId",$_REQUEST)
        || array_key_exists("skuId",$_REQUEST)
        )
    )
    {

        $action = $_REQUEST['action'];

            switch($action)
            {
                case "add":
                    $packageId = $_REQUEST['packageId'];
                    $skuId  = $_REQUEST['skuId'];
                    if (is_numeric($packageId) && is_numeric($skuId))
                        addSkuToPackage($packageId,$skuId);
                    break;
                case "remove":
                    $packageSkuItemId = $_REQUEST['packageSkuItemId'];
                    if (is_numeric($packageSkuItemId))
                        removePackageSkuItemById($packageSkuItemId);
                    break;
            }
            $u['status']='ok';
    }
}
echo json_encode($u);

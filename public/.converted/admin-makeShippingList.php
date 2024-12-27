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
        && array_key_exists("skuId",$_REQUEST))
    {
        $skuId = $_REQUEST['skuId'];
        makeFileForShipping($skuId);
    }
    if ($_REQUEST && is_array($_REQUEST)
        && array_key_exists("packageId",$_REQUEST))
    {
        $packageId = $_REQUEST['packageId'];
        makeFileForShippingPackages($packageId);
    }
}
//echo json_encode($u);

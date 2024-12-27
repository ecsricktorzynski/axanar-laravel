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
        && array_key_exists("skuId",$_REQUEST))
    {
        $action = $_REQUEST['action'];
        $userId = $_REQUEST['userId'];
        $skuId = $_REQUEST['skuId'];
        if (!empty($skuId))
        {
            $doChange = toggleShipped($action, $userId, $skuId);
            switch($doChange)
            {
                case "ok":
                    $u['status'] = 'ok';
                    break;
                case "hold":
                    $u['status'] = 'ok';
                    break;
                default:
                    $u['status'] = 'error';
            }
        } else {
            $u['status'] = 'error';
        }

    }
}
echo json_encode($u);

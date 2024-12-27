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
        && array_key_exists("id",$_REQUEST)
        && array_key_exists("old",$_REQUEST)
        && array_key_exists("new",$_REQUEST))
    {
        $id = $_REQUEST['id'];
        $oe = $_REQUEST['old'];
        $ne = $_REQUEST['new'];
        if (!empty($ne) && $ne != $oe)
        {
            $doChange = changeEmailById($id, $oe, $ne);
            switch($doChange)
            {
                case "ok":
                    $u['status'] = 'ok';
                    break;
                case "merged":
                    $u['status'] = 'merged';
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

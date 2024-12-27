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
        && array_key_exists("first",$_REQUEST)
        && array_key_exists("last",$_REQUEST)
        && array_key_exists("email",$_REQUEST))
    {
        $first = $_REQUEST['first'];
        $last = $_REQUEST['last'];
        $email = $_REQUEST['email'];

        $sendMail = 0;
        if (array_key_exists("sendmail",$_REQUEST))
        {
            $sendMail=(int)$_REQUEST['sendmail'];
        }

        $user = getUserByEmail($email);
        if ($user === null)
        {
            createNewUser($first, $last, $email);
            if ($sendMail==1)
            {
                $user = getUserByEmail($email);
                if ($user)
                {
                    $password = setResetLink($email);
                    sendSetupEmail($email, $password);
                }
            }
            $u['status']='ok';
        } else
        {
            $u['status']="emailAlready";
        }
    }
}
echo json_encode($u);

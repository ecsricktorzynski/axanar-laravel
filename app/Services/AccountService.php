<?php

namespace Ares\Services;

use Illuminate\Support\Facades\Auth;

class AccountService
{
    public static function isAdmin()
    {
        if (!Auth::check()) {
            return false;
        }
        return session('admin') ?? false;
    }

    public function newAccount()
    {

        $em = $_REQUEST['em'] ?? null;
        $key = $_REQUEST['key'] ?? null;
        $resetKey = $_POST['reset-key'] ?? false;

        if ($resetKey && !empty($_POST)) {
            $newPassword = $_POST['new-password'];
            $oldPassword = $_POST['new-passwordconf'];
            $currentPassword = $_POST['current-password'];
            $currentEmail = $_POST['current-email'];
            if ($newPassword != "" && $newPassword == $oldPassword) {
                $doChange = changePasswordWithKey($currentEmail, $resetKey, $newPassword);
                switch ($doChange) {
                    case "ok":
                        $msg = "Your information has been successfully updated";
                        $icon = "fa fa-info-circle";
                        $stat = "UPDATE SUCCESSFUL";
                        echo "<script type=\"text/JavaScript\">setTimeout(function(){location.href = '/login';},1000);</script>";
                        break;
                    case "email":
                        $msg = "Unknown email address";
                        $icon = "fa fa-info-circle";
                        $stat = "UPDATE FAILED";
                        break;
                    case "key":
                        $msg = "Incorrect password reset key";
                        $icon = "fa fa-info-circle";
                        $stat = "UPDATE FAILED";
                        break;
                }
            } else {
                $msg = "Your new passwords do not match";
                $icon = "fa fa-info-circle";
                $stat = "UPDATE FAILED";
            }
        } else {
            $currentEmail = $em;
        }
    }

}

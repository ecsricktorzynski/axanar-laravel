<?php
$confirmLoggedIn = true;
$adminOnly = true;
require_once __DIR__ . '/bootstrap.php';

if ($_REQUEST && array_key_exists("userId", $_REQUEST)) {
    $allClear = true;
    $userId = $_REQUEST['userId'];
    $result = getUserRecord($userId);
    if ($result) {
        $u = $result[0];
        addToAudit("impersonateOk", $u['id'], "user logged in");
        $_SESSION['userId'] = $u['id'];
        $_SESSION['admin'] = $u['admin'];
    } else {
        redirect('/login?m=unknown');
    }
}

redirect()->route('dashboard');

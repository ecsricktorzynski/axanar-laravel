<?php
$confirmLoggedIn = false;
require_once __DIR__ . '/resources/views/layouts/header.php';

$key = "";
$em = "";
if (array_key_exists("key", $_REQUEST)) {
    $key = $_REQUEST['key'];
}

if (array_key_exists("em", $_REQUEST)) {
    $em = $_REQUEST['em'];
}

if ($_POST && array_key_exists("reset-key", $_POST)) {
    $key = $_POST['reset-key'];
    $np = $_POST['new-password'];
    $op = $_POST['new-passwordconf'];
    $orig = $_POST['current-password'];
    $email = $_POST['current-email'];
    if ($np != "" && $np == $op) {
        $doChange = changePasswordWithKey($email, $key, $np);
        switch ($doChange) {
            case "ok":
                $msg = "Your information has been successfully updated";
                $icon = "fa fa-info-circle";
                $stat = "UPDATE SUCCESSFUL";
                echo "<script type=\"text/JavaScript\">setTimeout(function(){location.href = 'login.php';},1000);</script>";
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
    $email = $em;
}
?>
<div id="page-container">

    <?php
    $mediaTitle = 'Change Account Password';
    require_once __DIR__ . '/resources/views/layouts/navbar.php';
    ?>
    <section class="site-content site-section">
        <div class="container">
            <?php showMessages($msg, $stat, $icon); ?>
            <div class="col-sm-6 col-sm-offset-3 col-lg-4 col-lg-offset-4 site-block">
                <p>To set or change your <?= config('versionName') ?> password, please enter your current password, enter your new password twice, and then press
                    the "Change Password" button to confirm the change.</p>
                <p>Be sure to follow internet best practices by choosing a password for <?= config('versionName') ?> that is unique and that you have not used, are
                    not using, and will never use on any other internet account.</p>
                <form action="account-setpassword.php" method="post" class="form-horizontal">
                    <div class="form-group">
                        <div class="col-xs-12" style="margin-bottom:15px;">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="gi gi-envelope"></i></span>
                                <input type="email"
                                       id="current-email"
                                       name="current-email"
                                       class="form-control input-lg"
                                       value="<?php echo $email; ?>"
                                       placeholder="Email Address"
                                       required>
                                <input type="text" id="reset-key" name="reset-key" class="form-control input-lg" value="<?php echo $key; ?>" placeholder="Reset Key" required>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="gi gi-asterisk"></i></span>
                                <input type="password" id="new-password" name="new-password" class="form-control input-lg" placeholder="New Password" required>
                                <input type="password"
                                       id="new-passwordconf"
                                       name="new-passwordconf"
                                       class="form-control input-lg"
                                       data-match="#new-password"
                                       placeholder="Confirm New Password">
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-actions">
                        <div class="col-xs-6"></div>
                        <div class="col-xs-6 text-right">
                            <button type="submit" name="submit" value="Set Password" class="btn btn-sm btn-primary"><i class="fa fa-arrow-right"></i> Set Password</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </section>

    <?php require_once __DIR__ . '/resources/views/layouts/page-footer.php'; ?>
</div>

<a href="#" id="to-top"><i class="fa fa-angle-up"></i></a>

<?php require_once __DIR__ . '/resources/views/layouts/footer.php'; ?>

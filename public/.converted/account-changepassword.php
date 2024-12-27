<?php
/**
 * @var string $unknownPassword
 */

use Ares\Utility\Password;

$confirmLoggedIn = true;
require_once __DIR__ . '/resources/views/layouts/header.php';

$msg = $icon = $stat = null;

if (!empty($_POST) && !empty($_POST['new-password'])) {
    $orig = trim(filter_var($_POST['current-password'], FILTER_SANITIZE_STRING));
    $np = trim(filter_var($_POST['new-password'], FILTER_SANITIZE_STRING));
    $op = trim(filter_var($_POST['new-passwordconf'], FILTER_SANITIZE_STRING));

    if (empty($orig) || empty($np) || empty($op)) {
        $msg = 'Please fill out all required fields';
        $icon = "fa fa-info-circle";
        $stat = 'Incomplete Form';
    } else {
        if ($np === $op) {
            if (Password::change($orig, $np)) {
                $msg = 'Your password has been updated';
                $icon = "fa fa-info-circle";
                $stat = 'Password Changed';
            } else {
                $msg = 'Unable to change your password. Please try again later.';
                $icon = "fa fa-info-circle";
                $stat = 'Password Change Failure';
            }
        } else {
            $msg = 'The new passwords do not match';
            $icon = "fa fa-info-circle";
            $stat = 'Invalid Credentials';
        }
    }
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

            <div class="row">
                <div class="col-md-8 col-md-offset-2 site-block bigger-text">


                    <h2 class="site-heading">
                        <i class="fa fa-key"></i> <?= $mediaTitle ?>
                    </h2>

                    <p>To change your <?= config('versionName') ?> password, enter your current password, and then your new password twice. Press the "<strong>Change Password</strong>" button to confirm the change.
                    </p>
                    <p>Your password must be at least 8 characters long, and not one you've used before.</p>

                    <p class="heading-bottom-border">
                        If you have received an email with a password reset or setup code, click <a href="account-setpassword.php">here</a>.
                    </p>

                    <?php if ($unknownPassword) { ?>
                        <div class="alert alert-danger">
                            <h4><i class="fa fa-medkit"></i>Authentication Error</h4>
                            <p>
                                The credentials you entered are not correct.<br/>
                                Please try again, or click <a href="account-forgotpassword.php">here</a> to reset your password.
                            </p>
                        </div>
                    <?php } ?>


                    <form action="account-changepassword.php" method="post" class="form-horizontal">
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="gi gi-asterisk"></i></span>
                                    <input type="password" id="current-password" name="current-password" class="form-control input-lg" placeholder="Current Password">
                                </div>
                            </div>
                            <div class="col-md-offset-2 col-md-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="gi gi-asterisk"></i></span>
                                    <input type="password" id="new-password" name="new-password" class="form-control input-lg" placeholder="New Password">
                                </div>
                            </div>
                            <div class="col-md-offset-2 col-md-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="gi gi-asterisk"></i></span>
                                    <input type="password" id="new-password-2" name="new-passwordconf" class="form-control input-lg" placeholder="New Password Again">
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-actions">
                            <div class="col-md-offset-2 col-md-8 text-right">
                                <button type="submit" name="submit" value="Change Password" class="btn btn-primary"><i class="fa fa-chevron-right"></i>Change Password</button>
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

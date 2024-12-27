<?php
require_once __DIR__ . '/resources/views/layouts/header.php';

$showMsg = $showStatus = $wasPost = false;
if (array_key_exists('login-email', $_POST)) {
    if ($_POST['login-email']) {
        $wasPost = true;
        $email = $_POST['login-email'];
        $status = checkEmail($email);
        $showMsg = true;
        $showStatus = $status['status'];
        if ($showStatus) {
            $newPW = setResetLink($email);
            if (!is_null($newPW)) {
                sendPasswordEmail($email, $newPW);
            }
        }
    }
}
?>

<div id="page-container">

    <?php require_once __DIR__ . '/resources/views/layouts/navbar.php'; ?>

    <section class="site-content site-section">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 site-block bigger-text">
                    <h2 class="site-heading">
                        <i class="fa fa-key"></i> Password Reset
                    </h2>
                    <p>If you have already created a password for your account but cannot remember what it is, please enter your donor email address below. We'll email you a link to reset your password.</p>
                    <p>Remember, passwords are case sensitive.</p>
                    <hr/>

                    <?php if ($showMsg) { ?>
                        <?php if ($showStatus) { ?>
                            <div class="alert alert-success">
                                <h4><i class="fa fa-medkit"></i>RESET PASSWORD LINK SENT</h4>
                                <p>An email is on it's way with a link which will allow you to reset your password.</p>
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-danger">
                                <h4><i class="fa fa-medkit"></i>UNKNOWN EMAIL</h4>
                                <p>That email address is unknown or invalid. Please try again.</p>
                            </div>
                        <?php } ?>
                    <?php } ?>

                    <?php if (!$showMsg || ($showMsg && !$showStatus)) { ?>
                        <form action="account-forgotpassword.php" method="post" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-md-offset-2 col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="gi gi-envelope"></i></span>
                                        <input type="email" id="login-email" name="login-email" class="form-control input-lg required" required placeholder="Email Address">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group form-actions">
                                <div class="col-md-offset-2 col-md-8 text-right">
                                    <button type="submit" name="submit" value="Reset Password" class="btn btn-success"><i class="fa fa-email"></i>Reset Password</button>
                                </div>
                            </div>
                        </form>
                    <?php } ?>

                    <?php require_once app()->basePath('/resources/views/layouts/section-axanar-support.php'); ?>
                </div>
            </div>
        </div>
    </section>

    <?php require_once __DIR__ . '/resources/views/layouts/page-footer.php'; ?>
</div>

<a href="#" id="to-top"><i class="fa fa-angle-up"></i></a>

<?php require_once __DIR__ . '/resources/views/layouts/footer.php'; ?>



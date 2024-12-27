<?php
$confirmLoggedIn = true;
require_once __DIR__ . '/resources/views/layouts/header.php';

$showForm = true;

if (array_key_exists("new-email", $_REQUEST)) {
    $ne = $_REQUEST['new-email'];
    $oe = $_REQUEST['new-emailconf'];
    $orig = $_REQUEST['current-email'];
    if ($ne != "" && $ne == $oe) {
        $doChange = changeEmail($orig, $ne);
        switch ($doChange) {
            case "ok":
                $msg = "Your email has been successfully updated";
                $icon = "info";
                $stat = "UPDATE SUCCESSFUL";
                $showForm = false;
                break;
            case "merged":
                $msg = "Your account has been successfully merged";
                $icon = "alert";
                $stat = "MERGE SUCCESSFUL";
                $showForm = false;
                break;
            default:
                $msg = "Incorrect old email";
                $icon = "error";
                $stat = "UPDATE FAILED";
        }
    } else {
        $msg = "Your new email does not match";
        $icon = "fa fa-info-circle";
        $stat = "UPDATE FAILED";
    }
}

?>
<div id="page-container" xmlns="http://www.w3.org/1999/html">

    <?php
    $mediaTitle = 'Change Email';
    require_once __DIR__ . '/resources/views/layouts/navbar.php';
    ?>

    <script>
      function checkNewEmails() {
        var o = $('#new-email').val();
        var n = $('#new-emailconf').val();
        if (o != n) {
          alert('new email addresses do not match');
        }
        return o == n;
      }
    </script>
    <section class="site-content site-section">
        <div class="container">
            <?php showMessages($msg, $stat, $icon); ?>
            <div class="col-sm-6 col-sm-offset-3 col-lg-4 col-lg-offset-4 site-block">

                <?php
                if (!$showForm) {
                    redirect()->route('logout');
                    ?>
                    <p>Your info has been updated and you have now been logged out. You can click
                        <a href="index.php">here</a to return to the Log In page or wait 10 seconds to be auto-redirected</p>
                    <script>
                      $(document).ready(function() {
                        // Handler for .ready() called.
                        window.setTimeout(function() {
                          location.href = 'index.php';
                        }, 5000);
                      });
                    </script>
                <?php
                } else {
                ?>

                    <p>This form will allow you to update your email address to a new address. If the address already in use, then any current donor
                        perk packages will be transferred to that account. </p>
                    <p>Note that this will only transfer the packages and not the password, shipping address or any other user information.</p>
                    <p>Immediately on completion of the email address change, you will be logged out, and prompted to log in again with the new
                        email.</p>
                    <form action="account-changeemail.php" method="post" class="form-horizontal" data-toggle="validator" role="form">
                        <div class="form-group">
                            <div class="col-xs-12" style="margin-bottom:15px;">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="gi gi-asterisk"></i></span>
                                    <input type="text" id="current-email" name="current-email" class="form-control input-lg" placeholder="Current Email" required>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="gi gi-asterisk"></i></span>
                                    <input type="text" id="new-email" name="new-email" class="form-control input-lg" placeholder="New Email" required>
                                    <input type="text"
                                           id="new-emailconf"
                                           name="new-emailconf"
                                           class="form-control input-lg"
                                           data-match="#new-email"
                                           placeholder="Confirm New Email"
                                           required
                                           data-match-error="New email entries do not match.">
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-actions">
                            <div class="col-xs-6"></div>
                            <div class="col-xs-6 text-right">
                                <button type="submit" name="submit" value="Change Password" class="btn btn-sm btn-primary" onclick="return checkNewEmails();">
                                    <i class="fa fa-arrow-right"></i> Change Email
                                </button>
                            </div>
                        </div>
                    </form>
                <?php } ?>
            </div>

        </div>
    </section>

    <?php require_once __DIR__ . '/resources/views/layouts/page-footer.php'; ?>
</div>

<a href="#" id="to-top"><i class="fa fa-angle-up"></i></a>

<?php require_once __DIR__ . '/resources/views/layouts/footer.php'; ?>

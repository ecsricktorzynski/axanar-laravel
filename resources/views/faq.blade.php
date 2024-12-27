@extends('layouts.app');

@section('content')
    <?php showMessages(); ?>

    <a id="general-questions"></a>
    <h3 class="sub-header">General Questions</h3>
    <div id="faq1" class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <i class="fa fa-angle-right"></i>
                    <a class="accordion-toggle"
                        data-toggle="collapse"
                        data-parent="#faq1"
                        href="#faq1_q1">I believe that I have donated to a specific fundraiser,
                        but Ares Digital 3.0 doesn't reflect the donation.</a>
                </h4>
            </div>
            <div id="faq1_q1" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>
                        The most common reason for this is that donors are confused as to which fundraiser they have donated to. Please review the donation receipt
                        that you recieved by email at the time that the donation was made and confirm that you have actually donated to the fundraiser that you
                        believe you have.</p>
                    <p>
                        The second most common reason is that a donor has used one email address for one donation and then another email address for a later
                        donation. Since {{ config('app.versionName') }} only knows of a donation by the email address that was associated with it at the time the donation was
                        made, please check your donation receipt to verify which email address that you've used with each donation and then log in to Ares Digital
                        2.0 with the appropriate email address. If this applies to you then please see the F.A.Q. topic that covers merging multiple accounts in to
                        one for simpler account/donation management.
                    </p>
                    <p class="remove-margin">However if you feel that you have made a donation to a particular fundraiser for
                        <strong>Axanar</strong>, and it is not
                        showing up on {{ config('app.versionName') }}, please email us with your email address that you used for the donation and which level(s) that you donated
                        at
                        <i><u>support@axanarproductions.com</u></i> and we will gladly investigate the issue for you.
                    </p>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><h4 class="panel-title"><i class="fa fa-angle-right"></i>
                    <a class="accordion-toggle"
                        data-toggle="collapse"
                        data-parent="#faq1"
                        href="#faq1_q2"
                        style="text-decoration:none;color:black;">I took advantage of the
                        installment payment plan option on the most recent Indiegogo fundraiser. Why doesn't my installment plan donation show in {{ config('app.versionName') }}?</a>
                </h4>
            </div>
            <div id="faq1_q2" class="panel-collapse collapse">
                <div class="panel-body">
                    <p class="remove-margin">Installment plans are not officially tallied as donations until they have been paid in full, including shipping costs.
                        Once your installment plan has been paid in full then accounting will enter your information for that donation in to the system. For more
                        information on the status of your installment plan, please email
                        <i><u>support@axanarproductions.com</u></i>.</p>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><h4 class="panel-title"><i class="fa fa-angle-right"></i>
                    <a class="accordion-toggle"
                        data-toggle="collapse"
                        data-parent="#faq1"
                        href="#faq1_q5"
                        style="text-decoration:none;color:black;">Does {{ config('app.versionName') }} secure
                        internet traffic using SSL (Secure Sockets Layer)?</a></h4></div>
            <div id="faq1_q5" class="panel-collapse collapse">
                <div class="panel-body">
                    <p class="remove-margin">Yes. All transactions that happen on {{ config('app.versionName') }}'s website, including any sub-sites or client sites that match the
                        wildcard of https://*.axanar.com/, are secured by a modern SSL certificate and best practices on the method of encryption used to
                        secure the connection between your web browser and our server(s). You can and should verify that there is a padlock icon in your web
                        browser's address bar when using any of our sites to ensure a secure connection. Transactions that occur off of {{ config('app.versionName') }}'s domain
                        are beyond our control and may or may not be encrypted, so please use your best judgment.</p>
                </div>
            </div>
        </div>

        <a id="account-questions"></a>
        <h3 class="sub-header">Account Questions</h3>
        <div class="panel panel-default">
            <div class="panel-heading"><h4 class="panel-title"><i class="fa fa-angle-right"></i>
                    <a class="accordion-toggle"
                        data-toggle="collapse"
                        data-parent="#faq1"
                        href="#faq1_q3"
                        style="text-decoration:none;color:black;">I have used different email
                        addresses accross multiple donations and now I have several accounts on {{ config('app.versionName') }}. What can I do to merge them in to one account?</a>
                </h4>
            </div>
            <div id="faq1_q3" class="panel-collapse collapse">
                <div class="panel-body">
                    <p class="remove-margin">If you have more than one email address that was used across various donations, please email us at
                        <a href="mailto:support@axanarproductions.com">support@axanarproductions.com</a> and note which email address should be the surviving email
                        address and which email should be merged into it. Please note that we reserve the right to verify the secondary email address ownership in
                        case Kickstarter or Indiegogo's records don't sufficiently match for both. </p>
                </div>
            </div>
        </div>

        <!--
        <div class="panel panel-default">
            <div class="panel-heading"><h4 class="panel-title"><i class="fa fa-angle-right"></i> <a class="accordion-toggle" data-toggle="collapse" data-parent="#faq1" href="#faq1_q6" style="text-decoration:none;color:black;">Can I change the email address that I use to log in to Ares Digital 3.0?</a></h4></div>
            <div id="faq1_q6" class="panel-collapse collapse">
                <div class="panel-body">
                    <p class="remove-margin" >In the near future you will be able to change the email address on your Ares Digital 3.0 account.  We will not be releasing that function until Ares Digital 3.0 exits the active delvelopment phase, aka beta testing, in order to avoid any possibility of an issue with such changes being lost or reverted due to an issue with donor records during development.</p>
                </div>
            </div>
        </div>
        -->

        <a id="system-announcements"></a>
        <h3 class="sub-header">System Announcements</h3>
        <div class="panel panel-default">
            <div class="panel-heading"><h4 class="panel-title"><i class="fa fa-angle-right"></i>
                    <a class="accordion-toggle"
                        data-toggle="collapse"
                        data-parent="#faq1"
                        href="#faq1_q7"
                        style="text-decoration:none;color:black;">The latest announcements on
                        system performance and known issues of the {{ config('app.versionName') }} platform.</a>
                </h4>
            </div>
            <div id="faq1_q7" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>
                        <b>KICKSTARTER &amp; PAYPAL DONORS</b> &mdash; If there is a discrepancy in the quantity or dollar amount of any donation(s) that you have
                        made to Axanar then please email
                        <u>support@axanarproductions.com</u> with a copy of your donation receipt(s) from Kickstarter or PayPal and
                        it would be our pleasure to investigate the issue.</p>
                    <p>
                        <b>RETRO PACKAGE DONORS</b> &mdash; We update {{ config('app.versionName') }} about every 7-10 days with new retro package donations made on our website. If
                        you don't see your recent donation in the system and you donated more than 2 weeks ago, please email
                        <a href="mailto:support@axanarproductions.com">support@axanarproductions.com</a>.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

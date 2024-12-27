<?php
$confirmLoggedIn = true;
require_once __DIR__ . '/resources/views/layouts/header.php';
?>
<body>
<div id="page-container">

    <?php require_once __DIR__ . '/resources/views/layouts/navbar.php'; ?>

    <section class="site-content site-section">
        <div class="container">
            <?php showMessages(); ?>

            <h4><strong>Welcome to the public preview of the new <strong><?= ARES_VERSION_NAME ?></strong> fulfillment system.</strong></h4>
            <p>
                We're still in active development, as indicated by the red "BETA" icon embedded in to the logo at the top-left of every page, so please excuse any sawdust
                on the floors, or Gremlins in the machinery, as you navigate the site during this 'early bird access' period.<br/><br/>We're working very hard to get any
                remaining anomalies sorted out of the system as quickly as possible, and please be sure to also read and understand any important notifications or
                disclaimers that appear in the <a href="/faq.php#announcements">System Announcements</a> section of the <a href="/faq.php">FAQ</a>, and any other overt
                messages
                that may appear on certain pages, before contacting support. Thank you.</p>
            <br/>


            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Activities
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">
                            Completed Activities
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingTwo">
                        <h4 class="panel-title">
                            <a class="collapsed"
                                role="button"
                                data-toggle="collapse"
                                data-parent="#accordion"
                                href="#collapseTwo"
                                aria-expanded="false"
                                aria-controls="collapseTwo">
                                Collapsible Group Item #2
                            </a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                        <div class="panel-body">
                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingThree">
                        <h4 class="panel-title">
                            <a class="collapsed"
                                role="button"
                                data-toggle="collapse"
                                data-parent="#accordion"
                                href="#collapseThree"
                                aria-expanded="false"
                                aria-controls="collapseThree">
                                Collapsible Group Item #3
                            </a>
                        </h4>
                    </div>
                    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                        <div class="panel-body">
                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                        </div>
                    </div>
                </div>
            </div>


            <div class="table-responsive">
                <table class="table table-bordered table-vcenter perks-table">
                    <thead>
                    <tr>
                        <th colspan="2">FUNDRAISER / CAMPAIGN</th>
                        <th class="text-center">TOTAL</th>
                        <th class="text-center">DIGITAL PERKS</th>
                        <th class="text-center">PHYSICAL PERKS</th>
                        <th class="text-center">SPECIAL PERKS</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $campaigns = getCampaignsForUser();
                    if (!empty($campaigns)) {
                        foreach ($campaigns as $campaign) {
                            renderCampaign($campaign);
                        }
                    ?>
                    </tbody>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6">
                                <p class="text-center giant-text"><strong>No Campaigns Found For Your Email</strong></p>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
            <br/>

        </div>
    </section>


    <footer class="site-footer site-section" style="background-color:rgba(37, 37, 37, 0.0);">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-4" style="text-align:center;">
                    <h4 class="footer-heading" style="font-family:'SerpentineStd-Light' !important;">Visit Axanar</h4>
                    <ul class="footer-nav list-inline">
                        <li><a href="http://www.axanar.com" target="_blank" class="site-logo"><i class="gi gi-globe" style="font-size:1em;"></i></a></li>
                        <li><a href="https://www.facebook.com/StarTrekAxanar" target="_blank" class="site-logo"><i class="si si-facebook" style="font-size:1em;"></i></a></li>
                        <li><a href="https://www.twitter.com/StarTrekAxanar" target="_blank" class="site-logo"><i class="si si-twitter" style="font-size:1em;"></i></a></li>
                        <li><a href="https://instagram.com/startrekaxanar" target="_blank" class="site-logo"><i class="si si-instagram" style="font-size:1em;"></i></a></li>
                        <li><a href="https://www.pinterest.com/startrekaxanar" target="_blank" class="site-logo"><i class="si si-pinterest" style="font-size:1em;"></i></a></li>
                        <li><a href="https://plus.google.com/+StarTrekAxanarOfficial" target="_blank" class="site-logo"><i class="si si-google_plus" style="font-size:1em;"></i></a>
                        </li>
                        <li><a href="https://www.youtube.com/StarTrekAxanar" target="_blank" class="site-logo"><i class="si si-youtube" style="font-size:1em;"></i></a></li>
                    </ul>
                </div>
                <div class="col-sm-6 col-md-4" style="text-align:center;">
                    <h4 class="footer-heading"></h4>
                    <ul class="footer-nav list-inline"></ul>
                </div>
                <div class="col-sm-6 col-md-4" style="text-align:center;">
                    <h4 class="footer-heading" style="font-family:'SerpentineStd-Light' !important;letter-spacing: 2px;">Powered by <a href="http://axanar.com"
                                                                                                                                       target="_blank"
                                                                                                                                       style="color:#ffffff;text-decoration:none;"><strong
                                    style="color:#ffffaa;font-family:'SerpentineStd-Medium' !important;">ARES</strong>.digital</a></h4>
                    <ul class="footer-nav list-inline" style="font-family:'SerpentineStd-Light' !important;letter-spacing: 1px;">&nbsp;Copyright &copy;2015-2016 - All rights
                        reserved.
                    </ul>
                </div>
            </div>
        </div>
    </footer>


</div>

<?php require_once __DIR__ . '/resources/views/layouts/footer.php'; ?>

<?php
$confirmLoggedIn = true;
$adminOnly = true;
require_once __DIR__ . '/resources/views/layouts/header.php';
?>
<body xmlns="http://www.w3.org/1999/html">
<style>
    .list-group panel.active {
        background-color: #030;
        border-color: #aed248;
    }
</style>
<script>
  $('.list-group').on('click', '> a', function(e) {
    var $this = $(this);
    $('.list-group').find('.active').removeClass('active');
    $this.addClass('active');
  });
</script>
<div id="page-container">

    <?php require_once __DIR__ . '/resources/views/layouts/navbar.php'; ?>

    <section class="site-content site-section">
        <div class="container">


            <?php showMessages(); ?>
            <!--
                                <div class="text-center"><a href="http://www.axanar.com/donate/" target="_blank"><img src="tallyboards/3/axanar-indiegogo2.html" style="width:100%;max-width:1140px;max-height:510px;border:0px;"></a></div>
                                <br/>
            -->
            <div class="list-group">
                <h3>Fulfillment Dashboard</h3>
            </div>
            <div class="container">
                <div id="mainMenu">
                    <?php
                    $campaigns = getCampaigns();
                    if (!is_array($campaigns)) {
                        $campaigns = arraay();
                    }

                    foreach ($campaigns as $campaign) {
                        $campaignId = $campaign['campaign_id'];

                        $items = getPackagesForCampaign($campaignId);

                        if (!is_array($items)) {
                            $items = [];
                        }

                        ?>

                        <div><?php echo $campaign['name']; ?></div>
                        <div class="list-group panel">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Total Entitled</th>
                                    <th>Total Fulfilled</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                foreach ($items as $item) {
                                    $id = $item['package_id'];
                                    $v = @getListToShipForTier($id);
                                    $i = $v === false ? 'N/A' : count($v);
                                    $f = @getListShippedForTier($id);
                                    if ($f === false) {
                                        $f = 'N/A';
                                    } else {
                                        $f = count($f);
                                    }
                                    echo "<tr>";
                                    echo "<td><a href='admin-fulfillment-tier.php?packageId=" . $item['package_id'] . "'>" . $item['name'] . "</a></td><td>$i</td><td>$f</td>";
                                    echo "</tr>\n";
                                } ?>
                                </tbody>
                            </table>
                        </div>

                    <?php } ?>
                </div>
            </div>


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

<?php
$confirmLoggedIn = true;
require_once __DIR__ . '/resources/views/layouts/header.php';
?>
<div id="page-container">

    <?php
    $mediaTitle = 'Digital Perk Vault';
    require_once __DIR__ . '/resources/views/layouts/navbar.php';
    ?>

    <section class="site-content site-section">
        <div class="container">
            <?php showMessages(); ?>
            <div class="col-sm-12"><a href="dashboard.php" style="text-decoration:none;"><span class="label label-info"><strong><i class="gi gi-left_arrow"
                                                                                                                                   style="margin-bottom:3px;"></i>&nbsp;&nbsp;RETURN TO DASHBOARD</a>&nbsp;&nbsp;</strong></span></a>
                <br/><br/></div>
            <?php
            $campaignId = $_REQUEST['campaign'];

            $campaign = getCampaign($campaignId);

            if (is_array($campaign)) {
                $campaign = $campaign[0];
                $d = getUserDonationsForCampaign($campaignId);
                if (is_array($d)) {
                    foreach ($d as $c) {
                        if ($campaignId == $c['campaign_id']) {
                            ?>
                            <div class="col-sm-12">
                                <h4 class='page-header'><?php echo $campaign['name']; ?> >> <?php echo $c['pkg_name']; ?></h4>
                            </div>
                            <?php
                            $itms = getItemsForPackage($c['package_id']);
                            if (is_array($itms)) {
                                foreach ($itms as $itm) {
                                    //var_dump($itm);
                                    $img = "pta-ortiz-1.jpg";
                                    if ($itm['image_url'] != "") {
                                        $img = $itm['image_url'];
                                    }
                                    ?>
                                    <div class="col-md-4 visibility-none" data-toggle="animation-appear" data-animation-class="animation-fadeInQuick" data-element-offset="-100">
                                        <div class="store-item"></a>
                                            <div class="store-item-image">
                                                <center><img src="images/client/perks/1/<?php echo $img; ?>" alt="" class="img-responsive"></center>
                                                <?php
                                                if ($itm['file_type'] == "zip") {
                                                    echo "<img src=\"images/overlays/perk_photo_overlay_zip.png\" style=\"position:absolute;top:0;left:0;\" class=\"img-responsive\">";
                                                } ?>
                                            </div>
                                            <div class="store-item-info clearfix" style="background-color:#efefef;height:100px;">
                                                <center><span class="store-item-price" style="font-size:14px;"><?php echo $itm['name']; ?></span></center>
                                            </div>

                                            <div class="clearfix" style="text-align:center;margin-top:5px;">
                                                <?php
                                                $lnk = getUserId() . ":" . $itm['sku_id'];
                                                $lnk = encrypt_decrypt("encrypt", $lnk);
                                                $o = $itm['available'];
                                                $s = getShippedStatusForUserItemFull(getUserId(), $itm['sku_id']);
                                                $g = null;
                                                if ($s != null) {
                                                    $g = "SHIPPED: " . $s['dt'];
                                                }
                                                if ($o == 1) {
                                                    switch ($itm['type']) {
                                                        case 1:
                                                            echo "<a href=\"asset.php?hash=$lnk\">DOWNLOAD LINK</a<br/>";
                                                            break;
                                                        case 2:
                                                            if ($g != null) {
                                                                echo $g;
                                                            } else {
                                                                echo "<span>PENDING</span>";
                                                            }
                                                            break;
                                                        case 3:
                                                        case 4:
                                                            echo "<a href=\"asset.php?hash=$lnk\">CLICK TO VIEW</a<br/>";
                                                            break;
                                                    }
                                                } else {
                                                    echo "<span>PENDING</span>";
                                                }
                                                ?>

                                            </div>

                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        }
                    }
                }
            }
            ?>

            <!--

                                                <div class="col-md-4 visibility-none" data-toggle="animation-appear" data-animation-class="animation-fadeInQuick" data-element-offset="-100">
                                                    <div class="store-item">
                                                        <div class="store-item-image"><img src="images/client/perks/1/pta-ortiz-1.jpg" alt="" class="img-responsive"><img src="images/overlays/perk_photo_overlay_zip.png" style="position:absolute;top:0;left:0;" class="img-responsive"></div>
                                                        <div class="store-item-info clearfix" style="background-color:#efefef;height:100px;">
                                                            <span class="store-item-price themed-color-dark pull-right" style="padding-left:15px;">1MB</span>
                                                            <strong>Juan Ortiz 'Prelude to Axanar' Poster #1</strong>
                                                        </div>

                                                            <div class="clearfix" style="text-align:center;margin-top:5px;">DOWNLOAD OPTIONS<br/>
                                                                <a href="vault-download.html?u=1&c=1&l=75&m=db" target="_blank"><img src="images/icons/db_icon_25.png" style="width:28px;height:25px;border:0px;vertical-align:middle;" alt="Download from Dropbox" title="Download from Dropbox"></a> &nbsp; <a href="vault-download.html?u=1&c=1&l=75&m=od" target="_blank"><img src="images/icons/od_icon_25.png" style="width:41px;height:25px;border:0px;vertical-align:middle;" alt="Download from OneDrive" title="Download from OneDrive"></a> &nbsp; <a href="vault-download.html?u=1&c=1&l=75&m=gd" target="_blank"><img src="images/icons/gd_icon_25.png" style="width:25px;height:25px;border:0px;vertical-align:middle;" alt="Download from Google Drive" title="Download from Google Drive"></a>
                                                            </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-4 visibility-none" data-toggle="animation-appear" data-animation-class="animation-fadeInQuick" data-element-offset="-100">
                                                    <div class="store-item">
                                                        <div class="store-item-image"><img src="images/client/perks/1/pta-ortiz-2.jpg" alt="" class="img-responsive"><img src="images/overlays/perk_photo_overlay_zip.png" style="position:absolute;top:0;left:0;" class="img-responsive"></div>
                                                        <div class="store-item-info clearfix" style="background-color:#efefef;height:100px;">
                                                            <span class="store-item-price themed-color-dark pull-right" style="padding-left:15px;">2MB</span>
                                                            <strong>Juan Ortiz 'Prelude to Axanar' Poster #2</strong>
                                                        </div>

                                                            <div class="clearfix" style="text-align:center;margin-top:5px;">DOWNLOAD OPTIONS<br/>
                                                                <a href="vault-download.html?u=2&c=1&l=75&m=db" target="_blank"><img src="images/icons/db_icon_25.png" style="width:28px;height:25px;border:0px;vertical-align:middle;" alt="Download from Dropbox" title="Download from Dropbox"></a> &nbsp; <a href="vault-download.html?u=2&c=1&l=75&m=od" target="_blank"><img src="images/icons/od_icon_25.png" style="width:41px;height:25px;border:0px;vertical-align:middle;" alt="Download from OneDrive" title="Download from OneDrive"></a> &nbsp; <a href="vault-download.html?u=2&c=1&l=75&m=gd" target="_blank"><img src="images/icons/gd_icon_25.png" style="width:25px;height:25px;border:0px;vertical-align:middle;" alt="Download from Google Drive" title="Download from Google Drive"></a>
                                                            </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-4 visibility-none" data-toggle="animation-appear" data-animation-class="animation-fadeInQuick" data-element-offset="-100">
                                                    <div class="store-item">
                                                        <div class="store-item-image"><img src="images/client/perks/1/pta-titlecard.jpg" alt="" class="img-responsive"><img src="images/overlays/perk_photo_overlay_zip.png" style="position:absolute;top:0;left:0;" class="img-responsive"></div>
                                                        <div class="store-item-info clearfix" style="background-color:#efefef;height:100px;">
                                                            <span class="store-item-price themed-color-dark pull-right" style="padding-left:15px;">631MB</span>
                                                            <strong>'Prelude to Axanar' Mobile Version 720p (.MP4 - NO SUBS)</strong>
                                                        </div>

                                                            <div class="clearfix" style="text-align:center;margin-top:5px;">DOWNLOAD OPTIONS<br/>
                                                                <a href="vault-download.html?u=3&c=1&l=75&m=od" target="_blank"><img src="images/icons/od_icon_25.png" style="width:41px;height:25px;border:0px;vertical-align:middle;" alt="Download from OneDrive" title="Download from OneDrive"></a> &nbsp; <a href="vault-download.html?u=3&c=1&l=75&m=gd" target="_blank"><img src="images/icons/gd_icon_25.png" style="width:25px;height:25px;border:0px;vertical-align:middle;" alt="Download from Google Drive" title="Download from Google Drive"></a>
                                                            </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-4 visibility-none" data-toggle="animation-appear" data-animation-class="animation-fadeInQuick" data-element-offset="-100">
                                                    <div class="store-item">
                                                        <div class="store-item-image"><img src="images/client/perks/1/pta-titlecard.jpg" alt="" class="img-responsive"><img src="images/overlays/perk_photo_overlay_zip.png" style="position:absolute;top:0;left:0;" class="img-responsive"></div>
                                                        <div class="store-item-info clearfix" style="background-color:#efefef;height:100px;">
                                                            <span class="store-item-price themed-color-dark pull-right" style="padding-left:15px;">935MB</span>
                                                            <strong>'Prelude to Axanar' Desktop Version 720p (.MKV - SUBS)</strong>
                                                        </div>

                                                            <div class="clearfix" style="text-align:center;margin-top:5px;">DOWNLOAD OPTIONS<br/>
                                                                <a href="vault-download.html?u=4&c=1&l=75&m=od" target="_blank"><img src="images/icons/od_icon_25.png" style="width:41px;height:25px;border:0px;vertical-align:middle;" alt="Download from OneDrive" title="Download from OneDrive"></a> &nbsp; <a href="vault-download.html?u=4&c=1&l=75&m=gd" target="_blank"><img src="images/icons/gd_icon_25.png" style="width:25px;height:25px;border:0px;vertical-align:middle;" alt="Download from Google Drive" title="Download from Google Drive"></a>
                                                            </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-4 visibility-none" data-toggle="animation-appear" data-animation-class="animation-fadeInQuick" data-element-offset="-100">
                                                    <div class="store-item">
                                                        <div class="store-item-image"><img src="images/client/perks/1/pta-titlecard.jpg" alt="" class="img-responsive"><img src="images/overlays/perk_photo_overlay_zip.png" style="position:absolute;top:0;left:0;" class="img-responsive"></div>
                                                        <div class="store-item-info clearfix" style="background-color:#efefef;height:100px;">
                                                            <span class="store-item-price themed-color-dark pull-right" style="padding-left:15px;">2.18GB</span>
                                                            <strong>'Prelude to Axanar' Desktop Version 1080p (.MKV - SUBS)</strong>
                                                        </div>

                                                            <div class="clearfix" style="text-align:center;margin-top:5px;">DOWNLOAD OPTIONS<br/>
                                                                <a href="vault-download.html?u=5&c=1&l=75&m=od" target="_blank"><img src="images/icons/od_icon_25.png" style="width:41px;height:25px;border:0px;vertical-align:middle;" alt="Download from OneDrive" title="Download from OneDrive"></a> &nbsp; <a href="vault-download.html?u=5&c=1&l=75&m=gd" target="_blank"><img src="images/icons/gd_icon_25.png" style="width:25px;height:25px;border:0px;vertical-align:middle;" alt="Download from Google Drive" title="Download from Google Drive"></a>
                                                            </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-4 visibility-none" data-toggle="animation-appear" data-animation-class="animation-fadeInQuick" data-element-offset="-100">
                                                    <div class="store-item">
                                                        <div class="store-item-image"><img src="images/client/perks/1/pta-scriptcover.jpg" alt="" class="img-responsive"><img src="images/overlays/perk_photo_overlay_zip.png" style="position:absolute;top:0;left:0;" class="img-responsive"></div>
                                                        <div class="store-item-info clearfix" style="background-color:#efefef;height:100px;">
                                                            <span class="store-item-price themed-color-dark pull-right" style="padding-left:15px;">102MB</span>
                                                            <strong>'Prelude to Axanar' Illustrated Script</strong>
                                                        </div>

                                                            <div class="clearfix" style="text-align:center;margin-top:5px;">DOWNLOAD OPTIONS<br/>
                                                                <a href="vault-download.html?u=6&c=1&l=75&m=od" target="_blank"><img src="images/icons/od_icon_25.png" style="width:41px;height:25px;border:0px;vertical-align:middle;" alt="Download from OneDrive" title="Download from OneDrive"></a> &nbsp; <a href="vault-download.html?u=6&c=1&l=75&m=gd" target="_blank"><img src="images/icons/gd_icon_25.png" style="width:25px;height:25px;border:0px;vertical-align:middle;" alt="Download from Google Drive" title="Download from Google Drive"></a>
                                                            </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-4 visibility-none" data-toggle="animation-appear" data-animation-class="animation-fadeInQuick" data-element-offset="-100">
                                                    <div class="store-item">
                                                        <div class="store-item-image"><img src="images/client/perks/1/facebook-group.jpg" alt="" class="img-responsive"></div>
                                                        <div class="store-item-info clearfix" style="background-color:#efefef;height:100px;">
                                                            <span class="store-item-price themed-color-dark pull-right" style="padding-left:15px;"></span>
                                                            <strong>Access to 'Axanar Donors Group' on Facebook</strong>
                                                        </div>

                                                            <div class="clearfix" style="text-align:center;margin-top:5px;">&nbsp;<br/><img src="images/site/spacer.png" style="width:1px;height:25px;border:0px;vertical-align:middle;"></div>

                                                    </div>
                                                </div>
                                                -->
            <div class="col-md-4 visibility-none" data-toggle="animation-appear" data-animation-class="animation-fadeInQuick" data-element-offset="-100">
                <div class="store-item">
                    <div class="store-item-image"><img src="images/client/perks/1/ares-patch.jpg" alt="" class="img-responsive"></div>
                    <div class="store-item-info clearfix" style="background-color:#efefef;height:100px;">
                        <span class="store-item-price themed-color-dark pull-right" style="padding-left:15px;"></span>
                        <center><strong>Access to 'Axanar Donor Station'</strong><BR>PASSWORD IS "Ajax61"</center>
                    </div>

                    <div class="clearfix" style="text-align:center;margin-top:5px;"><a href="http://www.axanar.com/donate/donor-station/">CLICK TO ACCESS</a><br/>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <hr>
                <p style="text-align:justify;"><b>.ZIP COMPRESSION</b> &mdash; Most, if not all, of the digital downloads offered on <?= ARES_VERSION_NAME ?> have been compressed with the
                    industry standard .zip format using the WinZip program. If you experience trouble opening the .zip files using your desktop operating system's native .zip
                    functions then you may need to use a third-party program such as WinZip, WinRAR, 7-Zip, or Stuffit (Mac). If you are using a mobile device (smart phone, tablet,
                    etc.) to download the files then you will need an app to unzip them before they can be viewed.</p>
                <p style="text-align:justify;"><b>VIDEO PLAYBACK</b> &mdash; For the most effective playback of .MKV (Matroska) or .MP4 format video files, or any format of video
                    and audio files for that matter, <?= ARES_VERSION_NAME ?> recommends the wildly popular and open source VLC Media Player that is available for many operating systems
                    and devices. You may download this free program directly from the developer's website by <a href="http://www.videolan.org/vlc/index.html" target="_blank">clicking
                        here</a>.</p>
                <p style="text-align:justify;"><b>ANTI-VIRUS FALSE POSITIVES:</b> &mdash; Some anti-virus software does not care for .pdf files that are delivered in a .zip file
                    and may report a warning or a false positive when you attempt to download such files. All files offered on <?= ARES_VERSION_NAME ?> are wholly within our control and
                    have been thoroughly scanned before being offered to you, so it is our recommendation that you override the false positive and continue downloading if you
                    expereince this situation.</p>
                <br/>
            </div>


        </div>
    </section>

    <?php require_once __DIR__ . '/resources/views/layouts/page-footer.php'; ?>

</div>

<a href="#" id="to-top"><i class="fa fa-angle-up"></i></a>

<?php require_once __DIR__ . '/resources/views/layouts/footer.php'; ?>

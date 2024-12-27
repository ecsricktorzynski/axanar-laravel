<?php
$confirmLoggedIn = true;
$adminOnly = true;
require_once __DIR__ . '/resources/views/layouts/header.php';
?>
<body>
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

  var _package_id = 0;

  function addEntry(name, pid) {
    $('.modal-title')[0].innerHTML = name;
    _package_id = pid;
  }

  function addSkuItem() {
    if (_package_id > 0) {
      packageId = _package_id;
      skuId = $('#skuId').val();
      if (confirm('are you sure you want to add that sku item to the package?')) {
        $.ajax({
          type: 'POST', url: 'admin-package-adjust.php', data: 'action=add&packageId=' + packageId + '&skuId=' + skuId, success: function(msg) {
            result = JSON.parse(msg);
            if (result.status && result.status == 'ok') {
              $('#viewDetails').modal('hide');
              location = 'admin-campaigns.php';
            } else {
              alert('Something went wrong. Please try again!');
            }
          },
        });
      }
    }
  }

  function removeSkuItem(packageSkuItemId) {
    if (confirm('are you sure you want to remove that sku item from the package?')) {
      $.ajax({
        type: 'POST', url: 'admin-package-adjust.php', data: 'action=remove&packageSkuItemId=' + packageSkuItemId, success: function(msg) {
          result = JSON.parse(msg);
          if (result.status && result.status == 'ok') {
            //mTable.ajax.reload(null, true);
            $('#viewDetails').modal('hide');
            location = 'admin-campaigns.php';
          } else {
            alert('Something went wrong. Please try again!');
          }
        },
      });
    }
  }
</script>
<div id="page-container">

    <?php
    $mediaTitle = 'Administration Dashboard';
    require_once __DIR__ . '/resources/views/layouts/navbar.php';
    ?>

    <section class="site-content site-section">
        <div class="container">
            <div id="viewDetails" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" id="detail-header">Modal Header</h4>
                        </div>
                        <div class='alert alert-success fade in' style="display: none" id='userMsg'></div>
                        <div class="modal-body" id='modal-body'>
                            <div class="row">
                                <div class="col-sm-2">Add&nbsp;Sku&nbsp;Item</div>
                                <div class="col-sm-10" id="detail-skuId"><select id="skuId" value="skuId">
                                        <?php
                                        $itms = getSkuItems();
                                        foreach ($itms as $itm) {
                                            echo "<option value='" . $itm['sku_id'] . "'>" . $itm['name'] . "</option>";
                                        }
                                        ?>
                                    </select></div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" onclick="addSkuItem();$('#packageForm').modal('hide')">Save</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>


                    </div>


                </div>
            </div>

            <?php showMessages(); ?>
            <!--
                                <div class="text-center"><a href="http://www.axanar.com/donate/" target="_blank"><img src="tallyboards/3/axanar-indiegogo2.html" style="width:100%;max-width:1140px;max-height:510px;border:0px;"></a></div>
                                <br/>
            -->
            <div class="list-group">
                <h3>Campaign Administration</h3>
            </div>

            <?php
            $campaigns = getCampaigns();
            if (!is_array($campaigns)) {
                $campaigns = [];
            }
            ?>

            <div class="container">
                <div id="mainMenu">
                    <div class="list-group panel">
                        <?php
                        foreach ($campaigns as $campaign) {
                            $els = getElementsForCampaign($campaign['campaign_id']);
                            echo '<a href="#c_' .
                                 $campaign['campaign_id'] .
                                 '" class="list-group-item active" data-toggle="collapse" data-parent="#MainMenu">' .
                                 $campaign['campaign_id'] .
                                 ') ' .
                                 $campaign['name'] .
                                 '</a>';
                            echo '<div class="collapse" id="c_' . $campaign['campaign_id'] . '">';
                            if (is_array($els) && count($els) > 0) {
                                $oldPackage = "";
                                foreach ($els as $el) {
                                    $package = $el['packageName'];
                                    if ($oldPackage != $package) {
                                        $pid = $el['package_id'];
                                        $itms = getItemsForPackage($pid);
                                        $itmString = "<div><ul>";
                                        foreach ($itms as $itm) {
                                            $itmString .= "<li>" .
                                                          $itm['name'] .
                                                          " <span class=\"glyphicon glyphicon-trash\" onclick=\"removeSkuItem(" .
                                                          $itm['id'] .
                                                          ");\"></span></li>";
                                        }
                                        $itmString .= "</ul></div>";
                                        echo '<a href="#" class="list-group-item">' .
                                             $package .
                                             "&nbsp;<button class=\"btn btn-info\" data-toggle=\"modal\" data-target=\"#viewDetails\" onclick='addEntry(\"$package\", $pid)'>Add</button>" .
                                             $itmString .
                                             '<div style="display:none;">test</div></a>';
                                        $oldPackage = $package;
                                    }
                                }
                            }
                            echo '</div>';
                        } ?>
                    </div>
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

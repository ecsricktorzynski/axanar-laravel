<?php
$confirmLoggedIn = true;
$adminOnly = true;
require_once __DIR__ . '/resources/views/layouts/header.php';
$campaignsString = getPlatformsAndCampaigns();
?>
<div id="page-container">
    <?php require_once __DIR__ . '/resources/views/layouts/navbar.php'; ?>
    <section class="site-content site-section">
        <div class="container">
            <?php showMessages(null, null, null, false); ?>

            <div id="viewDetails" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" id="detail-header">Modal Header</h4>
                            <div>
                                <button type="button" class="btn btn-default" onclick="sendSetupEmail();">Send Setup Email
                                </button>&nbsp;
                                <button type="button"
                                        class="btn btn-default"
                                        data-dismiss="modal"
                                        onclick="deleteUser();">
                                    Delete User
                                </button>&nbsp;<button type="button"
                                                       class="btn btn-default"
                                                       data-dismiss="modal"
                                                       onclick="impersonateUser();">Impersonate
                                    User
                                </button>
                            </div>
                        </div>
                        <div class='alert alert-success fade in' style="display: none" id='userMsg'></div>
                        <div class="modal-body" id='modal-body'>
                            <div class="row">
                                <div class="col-sm-2">Last Login</div>
                                <div class="col-sm-10" id="detail-last-login"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2">Email</div>
                                <div class="col-sm-10"><p id="detail-email" data-editable></p>
                                    <button id="saveEml" type="button" class="btn btn-default" onclick="saveEmail();">Save Changed
                                        Email
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2">Reset&nbsp;Code</div>
                                <div class="col-sm-10" id="detail-reset"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2">Address</div>
                                <div class="col-sm-10" id="detail-address"></div>
                            </div>
                            <div class="row">
                                <hr>
                                <div class="col-sm-12">
                                    <strong>Campaigns:</strong>
                                    <div id="campaigns"></div>
                                    <div class="panel-group" id="accordion"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="button"
                                            class="btn btn-info btn-lg"
                                            data-toggle="modal"
                                            data-target="#packageForm">Add Package
                                    </button>
                                </div>
                                <div id="packageForm" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title" id="detail-header-package">Add Package</h4>
                                            </div>
                                            <div class="modal-body" id='modal-body-package'>
                                                <div class="row">
                                                    <div class="col-sm-2">Select</div>
                                                    <div class="col-sm-10" id="detail-packages">
                                                        <select class="selectpicker" id="newPackage">
                                                            <?php
                                                            $campaigns = getCampaigns();
                                                            foreach ($campaigns as $campaign) {
                                                                $packages = getPackagesForCampaign($campaign['campaign_id']);
                                                                echo "<optgroup label=\"" .
                                                                     $campaign['name'] .
                                                                     $campaign['campaign_id'] .
                                                                     "\">";
                                                                foreach ($packages as $package) {
                                                                    echo "<option value='" .
                                                                         $package['package_id'] .
                                                                         "'>" .
                                                                         $package['name'] .
                                                                         "</option>";
                                                                }
                                                                echo "</optgroup>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="new-amt">Donation Amount:</label>
                                                    <input type="text" class="form-control" id="new-amt">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button"
                                                        class="btn btn-default"
                                                        onclick="addPackage();$('#packageForm').modal('hide')">Save
                                                </button>
                                                <button type="button"
                                                        class="btn btn-default"
                                                        onclick='$("#packageForm").modal("hide")'>Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>


                    </div>


                </div>
            </div>

            <div class="list-group">
                <div class="container">
                    <div class="pull-right">
                        <button type="button" class="btn btn-warning btn-md" data-toggle="modal" data-target="#newUserForm"><i class="fa fa-plus"></i>Create New User</button>
                        <div id="newUserForm" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title" id="detail-header-new">New User Information</h4>
                                    </div>
                                    <div class='alert alert-success fade in' style="display: none" id='newUserMsg'></div>
                                    <div class="modal-body" id='modal-body-new'>
                                        <div class="form-group">
                                            <label for="new-first">First Name:</label>
                                            <input type="text" class="form-control" id="new-first">
                                        </div>
                                        <div class="form-group">
                                            <label for="new-last">Last Name:</label>
                                            <input type="text" class="form-control" id="new-last">
                                        </div>
                                        <div class="form-group">
                                            <label for="new-eml">Email:</label>
                                            <input type="text" class="form-control" id="new-eml">
                                        </div>
                                        <div class="form-group">
                                            <label for="new-eml">Send Initial Email:</label>
                                            <input type="checkbox" value='1' class="form-control" id="new-sendmail" checked>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" onclick="addNewUser();">Save</button>
                                        <button type="button"
                                                class="btn btn-default"
                                                data-dismiss="modal"
                                                onclick="clearValues();">Close
                                        </button>
                                    </div>


                                </div>


                            </div>
                        </div>
                    </div>

                    <form class="form form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="optPlatform">Donation Platform:</label>
                            <div class="col-sm-6">
                                <select class="form-control" id='optPlatform' name='optPlatform'>
                                    <option value>All</option>
                                    <option value='1'>Kickstarter</option>
                                    <option value='2'>Indiegogo</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="optCampaign">Campaign:</label>
                            <div class="col-sm-6">
                                <select class="form-control" id='optCampaign' name='optCampaign'>
                                    <option value=''>All</option>
                                    <?php echo $campaignsString; ?>
                                </select>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table id="members" class="row-border compact hover order-column" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>View Details</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>View Details</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <?php require_once __DIR__ . '/resources/views/layouts/page-footer.php'; ?>
</div>

<?php require_once __DIR__ . '/resources/views/layouts/footer.php'; ?>

<script>
  let userId = 0;
  let $userMsg = $('#userMsg');

  function sendSetupEmail() {
    if (userId > 0) {
      $.ajax({
        type: 'POST', url: 'sendSetupEmail.php', data: 'msg=setup&userId=' + userId, success: function(msg) {
          result = JSON.parse(msg);
          if (result.status && result.status === 'ok') {
            $userMsg[0].innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + '<strong>Success!</strong> Setup Message Sent Successfully';
            $userMsg.toggle();
          }
        },
      });
    }
  }

  function removePackage(packageId) {
    if (userId > 0) {
      if (confirm('are you sure you want to remove that package?')) {
        $.ajax({
          type: 'POST', url: 'admin-member-adjust.php', data: 'action=remove&userId=' + userId + '&packageId=' + packageId + '&amt=0', success: function(msg) {
            result = JSON.parse(msg);
            if (result.status && result.status === 'ok') {
              mTable.ajax.reload(null, true);
              $('#viewDetails').modal('hide');
            } else {
            }
          },
        });
      }
    }
  }

  function deleteUser() {
    if (userId > 0) {
      if (confirm('Are you sure you want to delete this user?')) {
        $.ajax({
          type: 'POST', url: 'adminDeleteUser.php', data: 'userId=' + userId, success: function(msg) {
            result = JSON.parse(msg);
            mTable.ajax.reload(null, true);
          },
        });
      }
    }
  }

  function impersonateUser() {
    if (userId > 0) {
      location = 'admin-impersonate.php?userId=' + userId;
    }
  }

  let mTable;
  let $originalEmail = $newEmail = '';

  function saveEmail() {
    if (userId > 0) {
      const $newUserMsg = $('#newUserMsg');
      if (confirm('are you sure you want to upade the email from ' + $originalEmail + ' to ' + $newEmail)) {
        $.ajax({
          type: 'POST', url: 'admin-member-email-update.php', data: 'id=' + userId + '&old=' + $originalEmail + '&new=' + $newEmail, success: function(msg) {
            result = JSON.parse(msg);
            if (result.status) {
              switch (result.status) {
                case 'ok':
                  $newUserMsg[0].innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + '<strong>Success!</strong> Adjusted';
                  break;
                case 'merged':
                  $newUserMsg[0].innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + '<strong>Success!</strong> Merged';
                  break;
              }
              $newUserMsg.toggle();
              mTable.ajax.reload(null, true);
              clearValues();

            } else {
              $newUserMsg[0].innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + '<strong>Error!</strong> User Not Saved';
              $newUserMsg.toggle();
            }
          },
        });
      }
    }
  }

  $(document).ready(function() {
    $('body').on('click', '[data-editable]', function() {
      let $el = $(this);
      $originalEmail = $el.text();

      let $input = $('<input id="eml" name="eml"/>').val($el.text());
      $el.replaceWith($input);

      let save = function() {

        if ($originalEmail !== $input.val()) {
          $('#saveEml').show();
        }

        let $p = $('<p id="detail-email" data-editable />').text($input.val());
        $newEmail = $input.val();
        $input.replaceWith($p);
      };

      /**
       We're defining the callback with `one`, because we know that
       the element will be gone just after that, and we don't want
       any callbacks leftovers take memory.
       Next time `p` turns into `input` this single callback
       will be applied again.
       */
      $input.one('blur', save).focus();

    });

    mTable = $('#members').DataTable({
      'processing': true, 'serverSide': true, 'ajax': {
        url: 'server_members.php', data: function(d) {
          d.platform = $('#optPlatform').val();
          d.campaign = $('#optCampaign').val();
        }, type: 'post',
      }, 'columnDefs': [
        {
          'targets': -1, 'data': null, 'defaultContent': '<button class="btn btn-info" data-toggle="modal" data-target="#viewDetails">Details</button>',
        }],
    });

    $('#optPlatform').change(function() {
      mTable.ajax.reload(null, true);
    });

    $('#optCampaign').change(function() {
      mTable.ajax.reload(null, true);
    });

    $('#members tbody').on('click', 'button', function() {
      let data = mTable.row($(this).parents('tr')).data();
      updateTable(data[0]);
    });

    function updateTable(_userId) {
      $('#saveEml').hide();
      $originalEmail = '';

      $.ajax({
        type: 'POST', url: 'admin-member-details.php', data: 'userId=' + _userId, success: function(msg) {
          user = JSON.parse(msg);
          $('.modal-title')[0].innerHTML = user.full_name;
          $('#detail-email')[0].innerHTML = '<a href=\'mailto:' + user.email + '\'>' + user.email + '</a>';
          $('#detail-last-login')[0].innerHTML = user.last_login;
          $('#detail-address')[0].innerHTML = user.address;
          rc = user.resetPass;

          if (rc == null || rc === '') rc = 'None Set'; else if (rc === 'used') rc = 'Already Used'; else rc +=
            ' use <a href=\'account-setpassword.php?em=' + user.email + '&key=' + rc + '\'>this</a> link';

          $('#detail-reset')[0].innerHTML = rc;
          userId = user.id;
          let c = '';
          let c2 = '';
          if (user.hasOwnProperty('campaigns')) {
            for (let i = 0; i < user.campaigns.length; i++) {
              camp = user.campaigns[i];
              c += '<ul class=\'list-group\'>';
              c += '<li class=\'list-group-item\'><h4>' + camp.name + '</h4></li>';
              pkgs = '';
              amt = 0;
              for (let v = 0; v < camp.packages.length; v++) {
                pack = camp.packages[v];
                l = '';
                for (let p = 0; p < pack.sku.length; p++) {
                  sku = pack.sku[p];

                  icon = '';
                  avail = parseInt(sku.available);
                  switch (parseInt(sku.type)) {
                    case 1:
                      icon = 'download';
                      if (avail === 0) icon = 'question-sign';
                      break;
                    case 4:
                      icon = 'globe';
                      break;
                    case 3:
                      icon = 'globe';
                      break;
                    case 2:
                      icon = 'question-sign';
                      if (avail === 1) {
                        icon = 'exclamation-sign';
                        if (sku.shipped === '1') {
                          icon = 'ok';
                        }
                      }

                  }

                  l += '<div class="row">';
                  l += '<div class="col-sm-10"><i style=\'margin-left:10px;\'>' + sku.name + '</i></div>';
                  l += '<div class=\'col-sm-2\'><span class=\'glyphicon glyphicon-' + icon + '\'></span></div>';
                  l += '</div>';
                }
                c += '<li class=\'list-group-item\'><strong>' + pack.name + '</strong>' + '<span class="badge">$' + pack.user_amount + '</span></li>';
                pkgs +=
                  '<a href="#" class="list-group-item">' +
                  pack.name +
                  ' <span class=\'glyphicon glyphicon-trash\' onclick=\'removePackage(' +
                  pack.package_id +
                  ');\'></span>' +
                  l +
                  '<span class="badge">$' +
                  pack.user_amount +
                  '</span></a>';

                //amt += (int)pack.user_amount;
              }
              c += '</ul>';

              c2 += '<div class="panel panel-default">	 \
                                    <div class="panel-heading">		 \
                                        <h4 class="panel-title">	 \
                                            <a data-toggle="collapse" data-parent="#accordion" href="#coll' +
                camp.campaign_id +
                '"><span class="glyphicon glyphicon-file"></span>' +
                camp.name +
                '</a>	 \
                                                       </h4>	 \
                                                   </div>	 \
                                                   <div id="coll' +
                camp.campaign_id +
                '" class="panel-collapse collapse">	 \
                                                       <div class="list-group">	 \
                                                           ' +
                pkgs +
                '	 \
                                                       </div>	 \
                                                   </div>	 \
                                               </div>';

            }
          }
          //$("#campaigns")[0].innerHTML = c;
          $('#accordion')[0].innerHTML = c2;
        },
      });
    }
  });

  function clearValues() {
    $('#new-first').val('');
    $('#new-last').val('');
    $('#new-eml').val('');
  }

  function addNewUser() {
    let first = $('#new-first').val();
    let last = $('#new-last').val();
    let email = $('#new-eml').val();
    const $newUserMsg = $('#newUserMsg');
    let sendmail = 0;
    if ($('#new-sendmail').is(':checked')) {
      sendmail = 1;
    }
    $.ajax({
      type: 'POST', url: 'admin-member-add.php', data: 'first=' + first + '&last=' + last + '&email=' + email + '&sendmail=' + sendmail, success: function(msg) {
        result = JSON.parse(msg);
        if (result.status && result.status === 'ok') {
          $newUserMsg[0].innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + '<strong>Success!</strong> User Successfully';
          $newUserMsg.toggle();
          mTable.ajax.reload(null, true);
          clearValues();
        } else {
          if (result.status && result.status === 'emailAlready') {
            $newUserMsg[0].innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + '<strong>Error!</strong> Email already in use';
          } else {
            $newUserMsg[0].innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + '<strong>Error!</strong> User Not Saved';
          }
          $newUserMsg.toggle();
        }
      },
    });
  }

  function addPackage() {
    if (userId > 0) {
      let pkg = $('#newPackage').val();
      let amt = $('#new-amt').val();
      $.ajax({
        type: 'POST', url: 'admin-member-adjust.php', data: 'action=add&userId=' + userId + '&packageId=' + pkg + '&amt=' + amt, success: function(msg) {
          result = JSON.parse(msg);
          if (result.status && result.status === 'ok') {
            mTable.ajax.reload(null, true);
          }
        },
      });
    }
  }
</script>

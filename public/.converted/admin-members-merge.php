<?php
$confirmLoggedIn = true;
$adminOnly = true;
require_once __DIR__ . '/resources/views/layouts/header.php';
?>
<body>
<style>
    body {
        margin: 50px;
    }

    #accordion .glyphicon {
        margin-right: 10px;
    }

    .panel-collapse > .list-group .list-group-item:first-child {
        border-top-right-radius: 0;
        border-top-left-radius: 0;
    }

    .panel-collapse > .list-group .list-group-item {
        border-width: 1px 0;
    }

    .panel-collapse > .list-group {
        margin-bottom: 0;
    }

    .panel-collapse .list-group-item {
        border-radius: 0;
    }
</style>
<div id="page-container">

    <?php
    $mediaTitle = 'Member Merge';
    require_once __DIR__ . '/resources/views/layouts/navbar.php';
    ?>
    <script>

      var userId = 0;
      var userEmail = null;

      function mergeUser() {
        if (userId > 0) {
          location = 'admin-members-merge.php?userId=' + userId;
        }
      }

      function sendSetupEmail() {
        if (userId > 0) {
          $.ajax({
            type: 'POST', url: 'sendSetupEmail.php', data: 'msg=merge&userId=' + userId, success: function(msg) {
              result = JSON.parse(msg);
              if (result.status && result.status == 'ok') {
                $('#userMsg')[0].innerHTML =
                  '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + '<strong>Success!</strong> Setup Message Sent Successfully';
                $('#userMsg').toggle();
              }
            },
          });
        }
      }

      $(document).ready(function() {
        var table = $('#members').DataTable({
          'processing': true, 'serverSide': true, 'ajax': {
            url: 'server_members.php', type: 'post',
          }, 'columnDefs': [
            {
              'targets': -1, 'data': null, 'defaultContent': '<button class="btn btn-info" data-toggle="modal" data-target="#viewDetails">Merge</button>',
            }],
        });

        $('#members tbody').on('click', 'button', function() {
          var data = table.row($(this).parents('tr')).data();
          var mergeInDataUser = data[0];

          if (data[2] == userEmail) {
            alert('You can\'t merge the user into themselves');
            return;
          }

          if (confirm('Are you sure that you want to merge ' + data[2] + ' into ' + userEmail + ' this cannot be undone.')) {
            alert('ok');
          }
          return;

          //alert( data[1] +"'s ID is: "+ data[ 0 ] );
          $.ajax({
            type: 'POST', url: 'admin-member-domerge.php', data: 'userId=' + data[0], success: function(msg) {
              user = JSON.parse(msg);
              $('.modal-title')[0].innerHTML = user.full_name;
              $('#detail-email')[0].innerHTML = '<a href=\'mailto:' + user.email + '\'>' + user.email + '</a>';
              $('#detail-last-login')[0].innerHTML = user.last_login;
              $('#detail-address')[0].innerHTML = user.address;
              userId = user.user_id;
              var c = '';
              var c2 = '';
              if (user.hasOwnProperty('campaigns')) {
                for (var i = 0; i < user.campaigns.length; i++) {
                  camp = user.campaigns[i];
                  c += '<ul class=\'list-group\'>';
                  c += '<li class=\'list-group-item\'><h4>' + camp.name + '</h4></li>';
                  pkgs = '';
                  amt = 0;
                  for (var v = 0; v < camp.packages.length; v++) {
                    pack = camp.packages[v];
                    l = '';
                    for (var p = 0; p < pack.sku.length; p++) {
                      sku = pack.sku[p];

                      icon = '';
                      avail = parseInt(sku.available);
                      switch (parseInt(sku.type)) {
                        case 1:
                          icon = 'download';
                          if (avail == 0) icon = 'question-sign';
                          break;
                        case 4:
                          icon = 'globe';
                          break;
                        case 3:
                          icon = 'globe';
                          break;
                        case 2:
                          icon = 'question-sign';
                          if (avail == 1) {
                            icon = 'exclamation-sign';
                            if (sku.shipped == '1') {
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
                    pkgs += '<a href="#" class="list-group-item">' + pack.name + l + '<span class="badge">$' + pack.user_amount + '</span></a>';

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
        });
      });
    </script>
    <section class="site-content site-section">
        <div class="container">


            <?php showMessages(null, null, null, false); ?>

            <?php
            $userId = 0;
            $usr = "";
            $eml = "";
            if ($_REQUEST['userId']) {
                $user = getUserAccountInfo($userId);
                if ($user) {
                    //var_dump($user);
                    $eml = $user[0]['email'];
                    //echo $eml;
                    $usr = $user[0]['full_name'];
                    echo "<script>userEmail='$eml'</script>";
                }
            }

            ?>

            <div>
                <form method="get">
                    <input type="hidden" name="userId" value="">
                    <div class="form-group">
                        <label for="usr">Email:</label>
                        <input type="text" class="form-control" id="eml" name='eml' value='<?php echo $eml; ?>'>
                    </div>
                </form>
                <blockquote>
                    <br>
                    <b>Primary Account:</b><br>
                    <b>Name:</b> <?php echo $usr; ?><br>
                    <b>Email:</b> <?php echo $eml; ?><br>
                </blockquote>
            </div>

            <div class="list-group">
                <div class="container">
                    <table id="members" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Merge</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Merge</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </section>


    <?php require_once __DIR__ . '/resources/views/layouts/page-footer.php'; ?>

</div>

<?php require_once __DIR__ . '/resources/views/layouts/footer.php'; ?>

<?php
$confirmLoggedIn = true;
$adminOnly = true;
require_once __DIR__ . '/resources/views/layouts/header.php';
?>
<body>
<div id="page-container">

    <?php
    $mediaTitle = 'Administration Dashboard';
    require_once __DIR__ . '/resources/views/layouts/navbar.php';
    ?>

    <section class="site-content site-section">
        <div class="container">


            <?php showMessages(); ?>
            <!--
                                <div class="text-center"><a href="http://www.axanar.com/donate/" target="_blank"><img src="tallyboards/3/axanar-indiegogo2.html" style="width:100%;max-width:1140px;max-height:510px;border:0px;"></a></div>
                                <br/>
            -->
            <div class="list-group">
                <h3>Admin Areas</h3>
            </div>

            <div class="container">
                <ul class="list-group">
                    <li class="list-group-item"><a href="admin-members.php">Member Administration</a></li>
                    <li class="list-group-item"><a href="admin-campaigns.php">Campaign Administration</a></li>
                </ul>
            </div>


        </div>
    </section>

    <?php require_once __DIR__ . '/resources/views/layouts/page-footer.php'; ?>
</div>

<?php require_once __DIR__ . '/resources/views/layouts/footer.php'; ?>

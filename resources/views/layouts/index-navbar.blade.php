<header>
    <div class="container">
        <a href="/index.php"
                class="site-logo"
                style="text-decoration:none;"><img alt class="home-logo" src="/images/client/AxanarDonors_v2_01.png"></a>
        <nav>
            <a href="javascript:void(0)"
                    class="btn btn-defaudonorlt site-menu-toggle visible-xs visible-sm"><i class="fa fa-bars"></i></a>
            <ul class="site-nav">
                <li class="visible-xs visible-sm"><a href="javascript:void(0)" class="site-menu-toggle text-center"><i
                                class="fa fa-times"></i></a></li>

                <li>
                    @auth
                        <a href="/logout"
                            class="btn btn-info"
                            style="text-shadow: 2px 2px 4px #000000;text-decoration:none;">
                            <i class="fa fa-sign-out"></i>&nbsp; Log Out
                    </a>
                    @endauth
                </li>

            </ul>
            <h2 class="text-center animation-fadeIn page-title">Fundraiser Fulfillment</h2>
        </nav>
    </div>
</header>

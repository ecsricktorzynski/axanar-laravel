@auth
<header>
    <div class="container">
        <a href="/dashboard" class="site-logo"><i class="gi gi-home"></i>&nbsp;&nbsp;<img class="home-logo" src="/images/client/AxanarDonors_v2_01.png" alt></a>
        <nav>
            <a href="#" class="btn btn-default site-menu-toggle visible-xs visible-sm"><i class="fa fa-bars"></i></a>
            <ul class="site-nav">
                <li class="visible-xs visible-sm">
                    <a href="#" class="site-menu-toggle text-center"><i class="fa fa-times"></i></a>
                </li>
                <li>
                    <a id="campaign" href="https://www.axanardonors.com/wp/product/axanar-march-shoot-fundraiser/"><i class="fa fa-dollar"></i>&nbsp; Donate!</a>
                </li>
                <li>
                    <a id="dashboard" href="/dashboard"><i class="fa fa-tachometer"></i>&nbsp; Dashboard</a>
                </li>
                <li>
                    <a id="faq" href="/faq"><i class="fa fa-question"></i>&nbsp; FAQ</a>
                </li>
                @if(Ares\Services\AccountService::isAdmin())
                <li>
                    <a id="account" href="#" class="site-nav-sub"><i class="fa fa-unlock-alt"></i>&nbsp;<i class="fa fa-angle-down site-nav-arrow"></i>Admin</a>
                    <ul>
                        <li><a href="/admin/members">Members</a></li>
                        <li><a href="/admin/campaigns">Campaigns</a></li>
                        <li><a href="/admin/fulfillment/tiers">Packages/Tiers</a></li>
                        <li><a href="/admin/fulfillment">Items</a></li>
                        <li><a href="/admin/formResetLink">Reset Link</a></li>
                    </ul>
                </li>
                @endif
                <li>
                    <a id="account" href="#" class="site-nav-sub">
                        <i class="fa fa-wrench"></i>&nbsp;<i class="fa fa-angle-down site-nav-arrow"></i>Account
                    </a>
                    <ul>
                        <li><a href="/account/shipping">Update Profile</a></li>
                        <li><a style="white-space: nowrap;" href="/account/email/change">Change Email Address</a>
                        <li><a href="/password/change">Change Password</a>
                        </li>
                    </ul>
                </li>

                <?php if (false && userHasStoreAccess()) { ?>
                    <li><a href="/donorStation.php" class="btn btn-warning" style="text-shadow: 2px 2px 4px #000000;"><i class="fa fa-tags"></i>&nbsp;
                            Donor Station</a></li>
                <?php } ?>
                <li><a href="/logout"><i class="fa fa-sign-out"></i>&nbsp; Log Out</a></li>
                <!-- removed impersonating link -->
            </ul>
        </nav>
    </div>
</header>
@endauth

@guest
<header>
    <div class="container">
        <a href="/" class="site-logo"><img src="/images/client/AxanarDonors_v2_01.png" style="width:250px;height:40px;"></a>
        <nav>
            <a href="javascript:void(0)" class="btn btn-defaudonorlt site-menu-toggle visible-xs visible-sm"><i class="fa fa-bars"></i></a>
            <ul class="site-nav">
                <li class="visible-xs visible-sm"><a href="#" class="site-menu-toggle text-center"><i class="fa fa-times"></i></a></li>

                <li>
                    <a href="/login" class="btn btn-info" style="text-shadow: 2px 2px 4px #000000;">
                        <i class="fa fa-sign-in"></i>&nbsp; Log In
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>
@endguest

@include('partials.media-header')
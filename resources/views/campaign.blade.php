<?php

/**
 * @var array $campaigns
 * @var array $inactiveCampaigns
 */
?>
@extends('layouts.app-wide')

@section('content')
<section class="site-content site-section">
    <div class=container>

        <section id=legend>
            <div class=container>
                <div class=row>
                    <div class="col-xs-12 col-md-6 text-center">
                        <img src="/images/axanar_shoot_2023.jpg" class="img-responsive center-block">
                    </div>
                    <div class="col-xs-12 col-md-6">

                        <h1 class="text-center">Axanar March Shoot Fundraiser!</h1>

                        

                        <div class="text-center font-weight-bold" style="margin: 40px; ">
                            <a href="https://www.axanardonors.com/wp/product/axanar-march-shoot-fundraiser/" target="_new"><button class="btn-lg btn-info" style="font-size: 60px;">Donate Now!</button></a>
                        </div>

                    </div>
                    <div class="col-xs-12 col-sm-6 text-center" style="margin-top: 20px;">

                        <a href="https://axanarproductions.com/axanar-newsletter/"><button class="btn btn-info">Sign Up for Axanar Newsletter</button></a>
                    </div>
                    <div class="col-xs-12 col-sm-6 text-center" style="margin-top: 20px;">

                        <a href="dashboard"><button class="btn btn-info">Continue to Donor Dashboard</button></a>
                    </div>
                </div>
            </div>
        </section> 
    </div>
</section>

@endsection

@push('body-scripts')
<script type="text/javascript">
    $(function() {
        $('[data-toggle="popover"]').popover();
    });
</script>
@endpush
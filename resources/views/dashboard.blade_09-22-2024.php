<?php
/**
 * @var array $campaigns
 * @var array $inactiveCampaigns
 */
?>
@extends('layouts.app-wide')

@section('content')
    <h2 class="site-heading">
        <i class="fa fa-dashboard"></i> Dashboard
    </h2>

    <?php showMessages(); ?>

    @if (session('status')) 
        <div class="alert alert-info">
            {{ session('status') }}
        </div>
    @endif
    <!-- partials called here -->
    @include('partials.campaigns', ['panelId' => 'active-projects', 'panelTitle' => 'Active Projects', 'campaigns' => $campaigns, 'showPerks'=> true])
    @include('partials.campaigns', ['panelId' => 'inactive-projects', 'panelTitle' => 'Completed Projects', 'campaigns' => $inactiveCampaigns, 'showPerks'=> false])

    <hr/>

    <section id="legend">
        <p>* There are three statuses for your perks: Fulfilled, Pending, and Download. Here is an explanation of each...</p>

        <p>
            <strong>FULFILLED</strong> - This means the item has been shipped to donors. If you did not receive this item, contact support@axanarproductions.com and we can help you further. Please be aware that items that were shipped recently might still be in transit, and depending on the date of shipment, we may request a short waiting period before shipping you a replacement.
        </p>

        <p>
            <strong>PENDING</strong> - This means the item in not yet available. There are many reasons for this status. One of the most common is that the specific perk cannot be produced until after the two remaining Axanar fan films are completed. This includes the DVD/Blu-ray of the final film and the illustrated script of the full film. I other cases, the item might not yet have been manufactured or it simply has not been shipped yet. For more information and the latest news on our perks, please check the Axanar Fulfillment Blog (link: https://axanarproductions.com/fulfillment-blog/ ) on our website.
        </p>

        <p>
            <strong>DOWNLOAD</strong> - This means the perk is digital and is ready for download. Simply click the "Download" button and save the file to your computer.
        </p>
    </section>

@endsection

@push('body-scripts')
    <script type="text/javascript">
      $(function() {
        $('[data-toggle="popover"]').popover();
      });
    </script>
@endpush

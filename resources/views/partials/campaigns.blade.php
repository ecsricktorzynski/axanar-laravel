<div class="panel panel-default">
    <div class="panel-heading">{{ $panelTitle ?? 'Campaigns' }}</div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table {{ $panelId }}">
                <thead>
                <tr>
                    <th colspan="2">Details</th>
                    <th class="text-center">Pledge</th>
                    @if($showPerks)
                        <th>Perk</th>
                        <th style="text-align: right;padding-right: 15px;">Status <i class="fa fa-info-circle tiny-icon"
                                data-toggle="tooltip"
                                data-placement="right"
                                title="See legend at the bottom of the page"></i></th>
                    @else
                        <th>No perks</th>
                        <th>Status</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @if(empty($campaigns))
                    <tr>
                        <td colspan="4">
                            <p class="text-center giant-text">
                                <strong>No projects found for your email address</strong>
                            </p>
                        </td>
                    </tr>
                @else
                    @foreach($campaigns as $campaign)
                        @include('partials.single-campaign', ['campaign' => $campaign, 'showPerks' => $showPerks ?? true])
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

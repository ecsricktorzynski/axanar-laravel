<?php
/**
 * @var array $campaign
 * @var bool  $showPerks
 */

use Ares\Models\Campaigns;
use Ares\Models\Packages;
use Illuminate\Support\Arr;

date_default_timezone_set('America/Los_Angeles');
$sd = strtotime($campaign['start_date']);
$sd = date('M d, Y', $sd);
$ed = strtotime($campaign['end_date']);
$ed = date('M d, Y', $ed);
$d = Campaigns::getUserDonations($campaign['campaign_id']);
$dc = 0;
$amt = 0.0;
$icon = "kickstarter-icon-32.png";
$iconAlt = "Kickstarter";
if ($campaign['provider'] == 2) {
    $iconAlt = "Indiegogo";
    $icon = "com.indiegogo.android.png";
}

$donor_page = "https://axanar.com/donor-roster/";

$perks = [
    'digitalAvailable' => 1,
    'physicalAvailable' => 0,
    'digitalPending' => 0,
    'physicalShipped' => 0,
    'physicalPending' => 0,
    'specialPending' => 0,
    'specialComplete' => 0,
    'specialAvailable' => 0,
];
$ext = "";

$ptip = null;
$perkList = [];
$packageTips = [];

$itemIDs = [];

foreach ($d as $p) {
    if ($p['campaign_id'] == $campaign['campaign_id']) {
        $dc++;
        $amt += $p['user_amount'];
        $pkg_id = $p['package_id'];
        $packageName = $p['pkg_name'];
        $items = Packages::getPackageItems($pkg_id);

        $packageTips[] = '$' . number_format($p['user_amount'], 0) . ' - ' . $p['pkg_name'];

        if (is_array($items)) {
            foreach ($items as $itm) {
                $plItemId = $itm['id'];
                $skuID = (int)$itm['sku_id'];
                if (in_array($skuID, $itemIDs)) {
                    continue;
                }
                $itemIDs[] = $skuID;
                $plItem = Arr::only($itm, ['name', 'accessLink', 'accessLinkURL']);
                switch ((int)$itm['type']) {
                    case 1:
                        if ((int)$itm['available'] == 1) {
                            $perks['digitalAvailable']++;
                            $plItem['status'] = 'Download';
                        } else {
                            $perks['digitalPending']++;
                            $plItem['status'] = 'Pending';
                        }
                        break;
                    case 2:
                        if ((int)$itm['available'] === 1) {
                            $perks['physicalAvailable']++;
                            $plItem['status'] = 'Download';
                            $t = getShippedStatusForUserItem(getUserId(), $skuID);
                            if ($t) {
                                $perks['physicalShipped']++;
                                $plItem['status'] = 'Shipped';
                            } else {
                                $perks['physicalPending']++;
                                $plItem['status'] = 'Pending';
                            }
                        } else {
                            $perks['physicalPending']++;
                            $plItem['status'] = 'Pending';
                        }
                        break;
                    case 3:
                        if ((int)$itm['available'] === 1) {
                            $perks['specialAvailable']++;
                            $plItem['status'] = 'Download';
                            //  Make donor's only forum link
                            if ($skuID === 3) {
                                $perks['digitalAvailable']++;
                                $plItem['status'] = 'Download';
                                $plItem['accessLinkText'] = 'Go to Forum';
                            } elseif ($skuID === 1) {
                                $perks['digitalAvailable']++;
                                $plItem['status'] = 'Download';
                                $plItem['accessLinkText'] = 'Go to Page';
                                
                                /*
                                $t = getShippedStatusForUserItem(getUserId(), $itm['sku_id']);
                                if ($t) {
                                    $perks['specialComplete']++;
                                    $plItem['status'] = 'Shipped';
                                } else {
                                    $perks['specialPending']++;
                                    $plItem['status'] = 'Pending';
                                }
                                */
                                $plItem['accessLinkURL'] = $donor_page;
                                
                            }
                        } else {
                            $perks['specialPending']++;
                            $plItem['status'] = 'Pending';
                        }
                        break;
                }

                switch ($plItem['status']) {
                    default:
                        $plItem['badge'] = 'btn-primary';
                        break;
                    case 'Download':
                        $plItem['badge'] = 'btn-success';
                        break;
                    case 'Shipped':
                        $plItem['badge'] = 'btn-warning';
                        break;
                }

                if ((int)$itm['sku_id'] === 3) {
                    $plItem['badge'] = 'btn-primary';
                }

                if ((int)$itm['sku_id'] === 1) {
                    $plItem['badge'] = 'btn-primary';
                }

                $plItem['statusClass'] = 'status-' . strtolower($plItem['status']);
                $plItem['isLink'] = ($plItem['status'] !== 'Pending');
                $perkList[$plItemId] = $plItem;
                unset($plItem);
            }
        }
    }
}

if ($dc != 1) {
    $ext = 's';
}

$vaultUrl = '/vault/assets/' . $campaign['campaign_id'];

if (!empty($packageTips)) {
    $ptip = implode('<br/>', $packageTips);
}
?>
<tr>
    <td style="width: 150px;">
        <a href="{{ $vaultUrl }}" target="_blank">
            <img src="{{ $campaign['image_url'] }}"
                alt="{{ $campaign['name'] }}"
                title="{{ $campaign['name'] }}" class="project-icon">
        </a>
    </td>
    <td style="min-width: 200px;">
        <h4><span><img src="/images/icons/{{ $icon }}"
                    class="platform-icon"
                    alt="{{$iconAlt}}"
                    title="{{$iconAlt}}"></span><span class="ml-10">{{$campaign['name']}}</span></h4>
        <small class="text-muted">{{$sd}} &mdash; {{ $ed }}</small>
    </td>

    <td class="text-center">
        <a href="{{ $campaign['web_url'] }}" target="_blank" style="white-space: nowrap;">
            <h4 data-content="{!! htmlentities($ptip) !!}"
                title="Pledge Breakdown"
                data-toggle="popover"
                data-html="true" data-trigger="hover">${{ $amt }}</h4>
        </a>
    </td>

    @if($showPerks)
        <td colspan="2">
            <table style="width:100%;">
                @foreach($perkList as $perk)
                    <tr>
                        <td style="text-align: left; padding: 0;">{{ $perk['name'] }}</td>
                        <td style="text-align: center;">
                            @if($perk['isLink'])
                              <a class="btn btn-xs {{$perk['badge']}} {{$perk['statusClass']}}"
                                    href="{{ $perk['accessLinkURL'] }}"
                                    target="_blank">{{ $perk['accessLinkText'] ?? $perk['status'] }}</a>
                            @else
                                <span class="{{$perk['statusClass']}}"><strong>{{ $perk['status'] }}</strong></span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </td>
    @endif
</tr>


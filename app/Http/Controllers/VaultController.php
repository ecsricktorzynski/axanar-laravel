<?php

namespace Ares\Http\Controllers;

use Ares\Models\Campaigns;
use Ares\Models\Packages;
use Ares\Models\SKUItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VaultController extends AuthenticatedController
{
    /**
     * Show the vault
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $campaignId
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, $campaignId = null)
    {
        
        if ($campaignId === null && ($campaignId = $request->get('campaign', null)) === null) {
            return redirect()->route('dashboard');
           
        }

        $campaign = Campaigns::find($campaignId);
        if (empty($campaign)) {
            return redirect()->route('dashboard');
        }

        $donations = Campaigns::getUserDonations($campaignId);
        foreach ($donations as $key => $pledge) {
            $donations[$key]['packageItems'] = Packages::getPackageItems($pledge['package_id']);
        }
      
        return view('vault', [
            'campaignId' => $campaignId,
            'campaign' => $campaign,
            'donations' => $donations,
        ]);
    }

    public function asset($hash)
    {
        $asset = encrypt_decrypt('decrypt', $hash);
        $assetElements = explode(':', $asset);

        if (!is_array($assetElements) || count($assetElements) < 2) {
            redirect()->route('dashboard')->with('status', 'Asset Not Found');
        }

        $userId = (int)$assetElements[0];
        $skuId = (int)$assetElements[1];
        
        if ($userId !== Auth::id()) {
            redirect()->route('dashboard')->with('status', 'Invalid User');
        }
        $item = SKUItems::find($skuId)->toArray();
        $pathToFile = public_path($item['link']);
        
        if (empty($item = SKUItems::find($skuId)->toArray())) {
            redirect()->route('dashboard')->with('status', 'SKU Item Not Found');
        }
        return response()->download($pathToFile);
        
    }
}

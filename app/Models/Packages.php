<?php

namespace Ares\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Packages extends BaseModel
{
    /** @inheritdoc */
    protected $table = 'Packages';
    /** @inheritdoc */
    protected $primaryKey = 'package_id';
    /** @inheritdoc */
    protected $casts = [
        'cost' => 'float',
    ];
    /** @inheritdoc */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function campaign()
    {
        return $this->hasMany(Campaigns::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userCampaignPackages()
    {
        return $this->hasMany(UserCampaignPackages::class);
    }

    /**
     * @param int $campaignID
     *
     * @return array
     */
    public static function getByCampaignID(int $campaignID)
    {
        $rows = static::where('campaign_id', $campaignID)
            ->orderBy('cost')
            ->get()
            ->toArray();

        return empty($rows) ? [] : $rows;
    }

    /**
     * @param int $packageId
     *
     * @return array
     */
    public static function getPackageItems($packageId)
    {
        $sql = <<<MYSQL
SELECT si.*, psi.*, p.sort_order_nbr
FROM SKUItems si
JOIN PackageSkuItems psi ON si.sku_id = psi.sku_id
JOIN Packages p ON psi.package_id = p.package_id
WHERE psi.package_id = ?
ORDER BY p.campaign_id, p.sort_order_nbr, si.name
MYSQL;

        $userId = Auth::id();
        $results = DB::select($sql, [$packageId]);
        $rows = static::resultsToArray($results);

        foreach ($rows as $key => $item) {
            $linkSku = $userId . ':' . $item['sku_id'];
            $linkSku = encrypt_decrypt('encrypt', $linkSku);
            $available = (int)$item['available'] ?? 0;
            $shipped = getShippedStatusForUserItemFull($userId, $item['sku_id']);
            $status = $shipped !== null ? 'SHIPPED: ' . $shipped['dt'] : null;
            $output = $defaultOutput = '<span>PENDING</span>';
            $outputUrl = null;

            if ($available === 1) {
                switch ($item['type']) {
                    case 1:
                        $output = '<a href="/vault/asset/' . $linkSku . '" target="_blank">DOWNLOAD LINK</a>';
                        $outputUrl = '/vault/asset/' . $linkSku;
                        break;
                    case 2:
                        $output = $status !== null ? $status : $defaultOutput;
                        $outputUrl = null;
                        break;
                    case 3:
                    case 4:
                        $output = '<a href="/vault/asset/' . $linkSku . '" target="_blank">CLICK TO VIEW</a>';
                        $outputUrl = '/vault/asset/' . $linkSku;
                        break;
                }
            } else {
                $output = $defaultOutput;
            }

            $rows[$key]['accessLink'] = $output;
            $rows[$key]['accessLinkURL'] = $outputUrl;
        }

        return $rows;
    }

    /**
     * @param int $packageId
     *
     * @return array
     */
    public static function getPackageItemsByCampaign($packageId, $campaignId)
    {
        $sql = <<<MYSQL
SELECT si.*, psi.*, p.sort_order_nbr
FROM SKUItems si
JOIN PackageSkuItems psi ON si.sku_id = psi.sku_id
JOIN Packages p ON psi.package_id = p.package_id
WHERE psi.package_id = ? AND p.campaign_id = ?
ORDER BY p.sort_order_nbr, si.name
MYSQL;

        $userId = Auth::id();
        $results = DB::select($sql, [$packageId, $campaignId]);
        $rows = static::resultsToArray($results);

        foreach ($rows as $key => $item) {
            $linkSku = $userId . ':' . $item['sku_id'];
            $linkSku = encrypt_decrypt('encrypt', $linkSku);
            $available = (int)$item['available'] ?? 0;
            $shipped = getShippedStatusForUserItemFull($userId, $item['sku_id']);
            $status = $shipped !== null ? 'SHIPPED: ' . $shipped['dt'] : null;
            $output = $defaultOutput = '<span>PENDING</span>';
            $outputUrl = null;

            if ($available === 1) {
                switch ($item['type']) {
                    case 1:
                        $output = '<a href="/vault/asset/' . $linkSku . '" target="_blank">DOWNLOAD LINK</a>';
                        $outputUrl = '/vault/asset/' . $linkSku;
                        break;
                    case 2:
                        $output = $status !== null ? $status : $defaultOutput;
                        $outputUrl = null;
                        break;
                    case 3:
                    case 4:
                        $output = '<a href="/vault/asset/' . $linkSku . '" target="_blank">CLICK TO VIEW</a>';
                        $outputUrl = '/vault/asset/' . $linkSku;
                        break;
                }
            } else {
                $output = $defaultOutput;
            }

            $rows[$key]['accessLink'] = $output;
            $rows[$key]['accessLinkURL'] = $outputUrl;
        }

        return $rows;
    }
}

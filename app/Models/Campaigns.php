<?php

namespace Ares\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Campaigns extends BaseModel
{
    /** @inheritdoc */
    protected $table = 'Campaigns';
    /** @inheritdoc */
    protected $primaryKey = 'campaign_id';
    /** @inheritdoc */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'active' => 'bool',
    ];
    /** @inheritdoc */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function packages()
    {
        return $this->hasOne(Packages::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userCampaignPackages()
    {
        return $this->hasMany(UserCampaignPackages::class);
    }

    /**
     * @param int      $active
     * @param int|null $userId
     *
     * @return array
     */
    public static function getByUserId(int $active = 1, int $userId = null)
    {
        $ucPackages =
            UserCampaignPackages::userId($userId ?? Auth::id() ?? 0)
                ->distinct()
                ->get('campaign_id')
                ->toArray();
        $idList = array_column($ucPackages, 'campaign_id');

        if (empty($idList)) {
            return [];
        }

        $campaigns = Campaigns::where('active', $active)->whereIn('campaign_id', $idList)->get()->toArray();
        return $campaigns;
    }

    /**
     * @param int      $campaignId
     * @param int|null $userId
     *
     * @return array|bool|mixed
     */
    public static function getUserDonations($campaignId, int $userId = null)
    {
        $campaign = static::find($campaignId);
        if (empty($campaign)) {
            return [];
        }

        $userId = $userId ?? Auth::id();

        $sql = <<<MYSQL
SELECT *, p.name AS pkg_name, p.sort_order_nbr 
FROM Campaigns c 
JOIN UserCampaignPackages uc ON c.campaign_id = uc.campaign_id 
JOIN Packages p ON p.package_id = uc.package_id 
WHERE uc.user_id = ?
  AND c.campaign_id = ?
ORDER BY p.sort_order_nbr, p.name  
MYSQL;

        $rows = DB::select($sql, [$userId, $campaignId]);
        return static::resultsToArray($rows);
    }

    /***
     * @param int $campaignId
     *
     * @return array
     */
    public static function getElements(int $campaignId)
    {
        $sql = <<<MYSQL
SELECT p.*, p.name as packageName
FROM Packages p 
WHERE campaign_id = ? 
ORDER BY campaign_id, cost;
MYSQL;

        $rows = DB::select($sql, [$campaignId]);
        return static::resultsToArray($rows);
    }

    /***
     * @param int $packageId
     *
     * @return array
     */
    public static function getNumPackagesToShip(int $packageId)
    {
        /*
        $sql = <<<MYSQL
SELECT COUNT(*) AS numToShip 
FROM Users u
JOIN UserCampaignPackages c ON u.user_id = c.user_id
JOIN Packages p ON p.package_id = c.package_id
JOIN Addresses a ON a.user_id = u.user_id
WHERE u.need_update = 0 AND p.package_id = ?;
MYSQL;
        */
        $sql = <<<MYSQL
        SELECT COUNT(*) AS numToShip 
        FROM Users u
        JOIN UserCampaignPackages c ON u.user_id = c.user_id
        JOIN Packages p ON p.package_id = c.package_id
        JOIN Addresses a ON a.user_id = u.user_id
        WHERE p.package_id = ?;
        MYSQL;

        $rows = DB::select($sql, [$packageId]);
        return static::resultsToArray($rows);
    }

    /***
     * @param int $packageId
     *
     * @return array
     */
    public static function getNumPackagesShipped(int $packageId)
    {
        /* 
        $sql = <<<MYSQL
SELECT COUNT(*) AS numShipped
FROM Users u
JOIN UserCampaignPackages c ON u.user_id = c.user_id
JOIN Packages p ON p.package_id = c.package_id
JOIN Addresses a ON a.user_id = u.user_id
LEFT JOIN UserSKUItems usi ON u.user_id = usi.user_id AND usi.package_id = p.package_id
WHERE need_update = 0 AND p.package_id = ? AND c.shipped = 1
MYSQL;
        */

        $sql = <<<MYSQL
SELECT COUNT(*) AS numShipped
FROM Users u
JOIN UserCampaignPackages c ON u.user_id = c.user_id
JOIN Packages p ON p.package_id = c.package_id
JOIN Addresses a ON a.user_id = u.user_id
LEFT JOIN UserSKUItems usi ON u.user_id = usi.user_id AND usi.package_id = p.package_id
WHERE p.package_id = ? AND c.shipped = 1
MYSQL;

        $rows = DB::select($sql, [$packageId]);
        return static::resultsToArray($rows);
    }

    /***
     * @param int $campaignId 
     * @param int $packageId
     * @param int $itemId
     *
     * @return array
     */
    public static function getListUsersPackagesItems($campaignId, $packageId, $itemId)
    {
   
        $sql = <<<MYSQL
        SELECT u.full_name, u.email, ucp.shipped
        FROM `UserCampaignPackages` ucp
        LEFT JOIN Users u on ucp.user_id=u.user_id
        LEFT JOIN PackageSkuItems psi ON ucp.package_id=psi.package_id
        LEFT JOIN SKUItems si ON psi.sku_id=si.sku_id
        WHERE ucp.campaign_id=? and ucp.package_id=? and si.sku_id=? and u.email IS NOT NULL
        ORDER BY u.full_name
        MYSQL;

        $rows = DB::select($sql, [$campaignId, $packageId, $itemId]);
        return static::resultsToArray($rows);
    }


    /***
     * @param int $campaignId
     *
     * @return array
     */
    public static function initSkuItems(int $campaignId)
    {
        $sql = <<<MYSQL
        SELECT  MAX(psi.sku_id)
        FROM PackageSkuItems psi 
        LEFT JOIN Packages p ON psi.package_id=p.package_id
        WHERE p.campaign_id=2
MYSQL;

        $maxsku_id = DB::select($sql, [$campaignId]);
        
        $skuArray = [];
        for ($i=0, $len=$maxsku_id; $i<$maxsku_id; $i++) {
            $skuArray[$campaignId][$i] = 0;
        }

        return $skuArray[$campaignId];
    }


}

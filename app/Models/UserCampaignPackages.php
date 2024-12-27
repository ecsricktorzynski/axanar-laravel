<?php

namespace Ares\Models;

use Ares\Database\Db;
use Ares\User;
use Carbon\Carbon;

/**
 * UserCampaignPackages
 * @method static $this userId(int $userId)
 */
class UserCampaignPackages extends BaseModel
{
    /** @inheritdoc */
    protected $table = 'UserCampaignPackages';
    /** @inheritdoc */
    protected $primaryKey = 'user_campaign_id';
    /** @inheritdoc */
    protected $casts = [
        'shipped' => 'integer',
        'user_amount' => 'float',
        'shipped_date' => 'datetime',
    ];
    /** @inheritdoc */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function users()
    {
        return $this->hasOne(User::class);
    }

    public function packages()
    {
        return $this->hasOne(Packages::class);
    }

    public function campaigns()
    {
        return $this->hasOne(Campaigns::class);
    }

    /**
     * @param static $query
     *
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    /**
     * @param static $query
     *
     * @return mixed
     */
    public function scopeInactive($query)
    {
        return $query->where('active', 0);
    }

    /**
     * @param static $query
     * @param int    $userId
     *
     * @return mixed
     */
    public function scopeUserId($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * @param int $action
     * @param int $userID
     * @param int $packageID
     *
     * @return string
     */
    public static function setShipped(int $action, int $userID, int $packageID)
    {
        $success = static::where(['user_id' => $userID, 'package_id' => $packageID])
            ->update([
                'shipped' => $action,
                'shipped_date' => Carbon::now()
            ]);
        return $success;
    }
}

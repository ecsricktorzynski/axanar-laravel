<?php

namespace Ares\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserSKUItems extends BaseModel
{
    /** @inheritdoc */
    protected $table = 'UserSKUItems';
    /** @inheritdoc */
    protected $primaryKey = 'user_sku_id';
    /** @inheritdoc */
    protected $casts = [
        'dt' => 'datetime',
        'sku_id' => 'integer',
        'user_id' => 'integer',
        'package_id' => 'integer',
        'shipped' => 'integer',
        'downloaded' => 'integer',
    ];
    /** @inheritdoc */
    public $timestamps = false;

    /**
     * @param $action
     * @param $userID
     * @param $skuID
     *
     * @return string
     */
    public static function setShipped(int $action, int $userID, int $skuID)
    {
        if ($action === 1) {
            $usiID =
                static::insertGetId([
                    'sku_id' => $skuID,
                    'user_id' => $userID,
                    'shipped' => $action,
                    'dt' => Carbon::now(),
                ]);
            if (!$usiID) {
                return false;
            }
            return true;
        }

        $success = static::where([
            'user_id' => $userID,
            'sku_id' => $skuID
        ])->update([
            'shipped' => $action,
            'dt' => Carbon::now(),
        ]);

        return $success;
    }
}

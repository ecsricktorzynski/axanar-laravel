<?php

namespace Ares\Models;

use Illuminate\Support\Facades\DB;

class SKUItems extends BaseModel
{
    /** @inheritdoc */
    protected $table = 'SKUItems';
    /** @inheritdoc */
    protected $primaryKey = 'sku_id';
    /** @inheritdoc */
    protected $casts = [
        'available_date' => 'datetime',
    ];
    /** @inheritdoc */
    public $timestamps = false;

    /**
     * @param int $packageId
     *
     * @return array
     */
    public static function getPackageItems(int $packageId)
    {
        $sql = <<<MYSQL
SELECT *
FROM SKUItems s 
JOIN PackageSkuItems i ON s.sku_id = i.sku_id 
WHERE i.package_id = ?
MYSQL;

        $rows = DB::select($sql, [$packageId]);
        return static::resultsToArray($rows);
    }
}

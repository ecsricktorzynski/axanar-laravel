<?php

namespace Ares\Models;

class Alerts extends BaseModel
{
    /** @inheritdoc */
    protected $table = 'Alerts';
    /** @inheritdoc */
    protected $primaryKey = 'alert_id';
    /** @inheritdoc */
    protected $casts = [
        'active' => 'bool',
    ];
    /** @inheritdoc */
    public $timestamps = false;

    public function userCampaignPackages()
    {
        return $this->belongsToMany(UserCampaignPackages::class);
    }
}

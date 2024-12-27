<?php

namespace Ares\Models;

class AlertsSeen extends BaseModel
{
    /** @inheritdoc */
    protected $table = 'AlertsSeen';
    /** @inheritdoc */
    protected $primaryKey = 'as_id';
    /** @inheritdoc */
    protected $casts = [
        'alert_id' => 'integer',
        'user_id' => 'integer',
    ];
    /** @inheritdoc */
    public $timestamps = false;
}

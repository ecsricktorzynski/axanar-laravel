<?php

namespace Ares\Models;

class Addresses extends BaseModel
{
    /** @inheritdoc */
    protected $table = 'Addresses';
    /** @inheritdoc */
    protected $primaryKey = 'address_id';
    /** @inheritdoc */
    protected $casts = [
        'default' => 'bool'
    ];
    /** @inheritdoc */
    public $timestamps = false;
}

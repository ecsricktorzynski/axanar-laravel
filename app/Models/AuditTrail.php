<?php

namespace Ares\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuditTrail extends BaseModel
{
    /** @inheritdoc */
    protected $table = 'AuditTrail';
    /** @inheritdoc */
    protected $primaryKey = 'audit_id';
    /** @inheritdoc */
    protected $casts = [
        'dt' => 'timestamp',
        'createTimestamp' => 'timestamp',
    ];
    /** @inheritdoc */
    public $timestamps = false;
}

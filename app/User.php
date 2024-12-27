<?php

namespace Ares;

use Ares\Models\AuditTrail;
use Ares\Models\BaseModel;
use Ares\Models\UserCampaignPackages;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Lab404\Impersonate\Models\Impersonate;

/**
 * App User class
 * @method array toArray()
 * @method $this distinct()
 * @method static Builder|EloquentBuilder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder|EloquentBuilder whereRaw($clause, $params = [])
 * @method static Builder|EloquentBuilder join($table, $one, $operator = null, $two = null, $type = 'inner', $where = false)
 * @method static Builder|EloquentBuilder joinWhere($table, $one, $operator, $two, $type = 'inner')
 * @method static Builder|EloquentBuilder leftJoin($table, $first, $operator = null, $second = null)
 * @method static Builder|EloquentBuilder leftJoinWhere($table, $one, $operator, $two)
 * @method static Builder|EloquentBuilder rightJoin($table, $first, $operator = null, $second = null)
 * @method static Builder|EloquentBuilder rightJoinWhere($table, $one, $operator, $two)
 * @method static Builder|EloquentBuilder orWhere($column, $operator = null, $value = null)
 * @method static Builder|EloquentBuilder orWhereRaw($sql, array $bindings = [])
 * @method static Builder|EloquentBuilder whereBetween($column, array $values, $boolean = 'and', $not = false)
 * @method static Builder|EloquentBuilder orWhereBetween($column, array $values)
 * @method static Builder|EloquentBuilder whereNotBetween($column, array $values, $boolean = 'and')
 * @method static Builder|EloquentBuilder orWhereNotBetween($column, array $values)
 * @method static Builder|EloquentBuilder whereNested(\Closure $callback, $boolean = 'and')
 * @method static Builder|EloquentBuilder addNestedWhereQuery($query, $boolean = 'and')
 * @method static Builder|EloquentBuilder whereExists(\Closure $callback, $boolean = 'and', $not = false)
 * @method static Builder|EloquentBuilder orWhereExists(\Closure $callback, $not = false)
 * @method static Builder|EloquentBuilder whereNotExists(\Closure $callback, $boolean = 'and')
 * @method static Builder|EloquentBuilder orWhereNotExists(\Closure $callback)
 * @method static Builder|EloquentBuilder whereIn($column, $values, $boolean = 'and', $not = false)
 * @method static Builder|EloquentBuilder orWhereIn($column, $values)
 * @method static Builder|EloquentBuilder whereNotIn($column, $values, $boolean = 'and')
 * @method static Builder|EloquentBuilder orWhereNotIn($column, $values)
 * @method static Builder|EloquentBuilder whereNull($column, $boolean = 'and', $not = false)
 * @method static Builder|EloquentBuilder orWhereNull($column)
 * @method static Builder|EloquentBuilder whereNotNull($column, $boolean = 'and')
 * @method static Builder|EloquentBuilder orWhereNotNull($column)
 * @method static Builder|EloquentBuilder whereDate($column, $operator, $value, $boolean = 'and')
 * @method static Builder|EloquentBuilder whereDay($column, $operator, $value, $boolean = 'and')
 * @method static Builder|EloquentBuilder whereMonth($column, $operator, $value, $boolean = 'and')
 * @method static Builder|EloquentBuilder whereYear($column, $operator, $value, $boolean = 'and')
 * @method static Builder|EloquentBuilder dynamicWhere($method, $parameters)
 * @method static Builder|EloquentBuilder groupBy()
 * @method static Builder|EloquentBuilder having($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder|EloquentBuilder orHaving($column, $operator = null, $value = null)
 * @method static Builder|EloquentBuilder havingRaw($sql, array $bindings = [], $boolean = 'and')
 * @method static Builder|EloquentBuilder orHavingRaw($sql, array $bindings = [])
 * @method static Builder|EloquentBuilder orderBy($column, $direction = 'asc')
 * @method static \ChaoticWave\BlueVelvet\Models\BaseModel latest($column = 'created_at')
 * @method static BaseModel oldest($column = 'created_at')
 * @method static Builder|EloquentBuilder orderByRaw($sql, $bindings = [])
 * @method static BaseModel offset($value)
 * @method static Builder|EloquentBuilder skip($value)
 * @method static Builder|EloquentBuilder limit($value)
 * @method static Builder|EloquentBuilder take($value)
 * @method static Builder|EloquentBuilder forPage($page, $perPage = 15)
 * @method static Builder|EloquentBuilder union($query, $all = false)
 * @method static Builder|EloquentBuilder unionAll($query)
 * @method static Builder|EloquentBuilder lock($value = true)
 * @method static Builder|EloquentBuilder lockForUpdate()
 * @method static Builder|EloquentBuilder sharedLock()
 * @method static string  toSql()
 * @method static BaseModel find($id, $columns = ['*'])
 * @method static BaseModel findOrFail($id, $columns = ['*'])
 * @method static BaseModel pluck($column)
 * @method static BaseModel first($columns = ['*'])
 * @method static BaseModel firstOrFail($columns = ['*'])
 * @method static BaseModel firstOrCreate($attributes = [])
 * @method static Collection get($columns = ['*'])
 * @method static Collection getFresh($columns = ['*'])
 * @method static Collection runSelect()
 * @method static Collection paginate($perPage = 15, $columns = ['*'])
 * @method static Collection simplePaginate($perPage = 15, $columns = ['*'])
 * @method static int getCountForPagination()
 * @method static mixed backupFieldsForCount()
 * @method static mixed restoreFieldsForCount()
 * @method static mixed chunk($count, callable $callback)
 * @method static mixed lists($column, $key = null)
 * @method static mixed getListSelect($column, $key)
 * @method static string implode($column, $glue = null)
 * @method static mixed exists()
 * @method static int count($columns = '*')
 * @method static mixed min($column)
 * @method static mixed max($column)
 * @method static mixed sum($column)
 * @method static mixed avg($column)
 * @method static mixed aggregate($function, $columns = ['*'])
 * @method static mixed insert(array $values)
 * @method static int insertGetId(array $values, $sequence = null)
 * @method static mixed truncate()
 * @method static Builder|EloquentBuilder raw($value)
 * @method static create(array $array)
 *
 * @property int $user_id
 * @property int $admin
 */
class User extends Authenticatable
{
    use Notifiable, Impersonate;

    /** @inheritdoc */
    protected $table = 'Users';
    /** @inheritdoc */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'donor_name',
        'full_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'need_update' => 'integer',
        'admin' => 'boolean',
        'last_login' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function userCampaignPackages()
    {
        return $this->belongsToMany(UserCampaignPackages::class);
    }

    /**
     * @param string      $action
     * @param string|int  $new_val
     * @param string|null $notes
     * @param array       $metadata
     *
     * @return array|bool|int|\mysqli_result
     */
    public static function audit(string $action, $new_val, string $notes = null, array $metadata = [])
    {
        $metadata = json_encode($metadata, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
        $userId = Auth::id();

        $ip = getenv('HTTP_CLIENT_IP')
            ?: getenv('HTTP_X_FORWARDED_FOR')
                ?: getenv('HTTP_X_FORWARDED')
                    ?: getenv('HTTP_FORWARDED_FOR')
                        ?: getenv('HTTP_FORWARDED')
                            ?: getenv('REMOTE_ADDR');

        return AuditTrail::insert([
            'actor_id' => $userId,
            'action' => $action,
            'new_val' => $new_val,
            'notes' => $notes,
            'ip' => $ip,
            'metadata' => $metadata,
            'dt' => Date::now()
        ]);
    }
}

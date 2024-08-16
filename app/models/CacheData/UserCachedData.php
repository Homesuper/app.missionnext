<?php


namespace MissionNext\Models\CacheData;


use MissionNext\Models\Subscription\Subscription;

class UserCachedData extends AbstractCachedData
{
    protected $fillable = array('user_id', 'data');

    protected  static $tableName = null;
    protected  static $tableRolePrefix = null;

    protected  static $tablePrefix = 'cached_profile';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {

        return $this->hasMany(Subscription::class, 'user_id', 'id');
    }
} 
<?php


namespace MissionNext\Models\Subscription;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MissionNext\Models\ModelInterface;
use MissionNext\Models\Observers\GlobalSubscriptionObserver;
use MissionNext\Models\User\User;

class GlobalSubscription extends Model implements ModelInterface
{
    protected static function boot()
    {
        parent::boot();
        parent::observe(new GlobalSubscriptionObserver());
    }

    protected $table = 'global_subscriptions';

    protected $fillable =
        [ 'user_id',   'is_recurrent', 'authorize_id',
            'start_date', 'end_date', 'status'];

    const STATUS_ACTIVE = 'active',
        STATUS_EXPIRED = 'expired',
        STATUS_GRACE = 'grace',
        STATUS_CLOSED = 'closed';

    public $timestamps = false;

    protected $appends = ['statuses', 'days_left'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {

        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return array
     */
    public function getDates()
    {

        return ['start_date', 'end_date'];
    }

    /**
     * @return array
     */
    public function getStatusesAttribute()
    {

        return [static::STATUS_ACTIVE, static::STATUS_EXPIRED, static::STATUS_GRACE];
    }

    /**
     * @return mixed
     */
    public function getDaysLeftAttribute()
    {

        return $this->end_date->diffInDays(Carbon::now(), false) * -1;
    }


    /**
     * @return array
     */
    public static function statuses()
    {

        return [static::STATUS_ACTIVE, static::STATUS_EXPIRED, static::STATUS_GRACE];
    }

} 
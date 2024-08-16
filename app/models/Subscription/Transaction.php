<?php

namespace MissionNext\Models\Subscription;


use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable =
        [ 'transaction_id', 'comment', 'amount'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subscriptions()
    {

        return $this->belongsToMany(Subscription::class, 'transactions_subscriptions', 'transaction_id', 'subscription_id');
    }

} 
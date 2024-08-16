<?php


namespace MissionNext\Models\Affiliate;

use Illuminate\Database\Eloquent\Model as Eloquent;
use MissionNext\Models\ModelInterface;
use MissionNext\Models\User\User as UserModel;

class Affiliate extends Eloquent implements ModelInterface
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_CANCELLED  = 'cancelled';

    const TYPE_REQUESTER = 'requester';
    const TYPE_APPROVER = 'approver';
    const TYPE_ANY = 'any';

    protected $table = "affiliates";

    protected $fillable = array('affiliate_approver', 'affiliate_requester', 'status', 'affiliate_approver_type', "app_id");

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approver()
    {

        return $this->belongsTo(UserModel::class, 'affiliate_approver');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function requester()
    {

        return $this->belongsTo(UserModel::class, 'affiliate_requester');
    }

} 
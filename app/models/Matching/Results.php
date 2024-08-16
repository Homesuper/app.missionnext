<?php


namespace MissionNext\Models\Matching;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use MissionNext\Models\ModelInterface;

class Results extends Model implements ModelInterface
{
    protected $table = "matching_results";

    protected $primaryKey = null;

    public $incrementing = false;

    protected $fillable = ["user_type", "user_id", "for_user_id", "data", "for_user_type"];

    /**
     * @param $query
     * @param $forUserType
     * @param $userType
     * @param $forUserId
     * @return Builder
     */
    public function scopeMatchingResults($query, $forUserType, $userType, $forUserId )
    {

       return
           $query->where("for_user_type","=", $forUserType)
              ->where("user_type", "=", $userType)
              ->where("for_user_id", "=", $forUserId);
    }
} 
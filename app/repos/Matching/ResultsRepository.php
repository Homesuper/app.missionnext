<?php


namespace MissionNext\Repos\Matching;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use MissionNext\Api\Service\DataTransformers\UserCachedDataStrategy;
use MissionNext\Api\Service\DataTransformers\UserCachedTransformer;
use MissionNext\Api\Service\Matching\TransData;
use MissionNext\Facade\SecurityContext;
use MissionNext\Models\CacheData\UserCachedDataTrans;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\Matching\Results;
use MissionNext\Models\Subscription\Partnership;
use MissionNext\Models\Subscription\Subscription;
use MissionNext\Repos\AbstractRepository;
use MissionNext\Api\Auth\SecurityContext as SC;

class ResultsRepository extends AbstractRepository implements ResultsRepositoryInterface
{
    protected $modelClassName = Results::class;

    const PAGINATION = 100;

    /**
     * @return Results
     */
    public function getModel()
    {

        return $this->model;
    }

    /**
     * @return SC
     */
    public function securityContext()
    {

        return SecurityContext::getInstance();
    }

    /**
     * @param $forUserType
     * @param $userType
     * @param $forUserId
     * @param $options
     *
     * @return array
     */
    public function matchingResults($forUserType, $userType, $forUserId, $options = null)
    {
        if(isset($options))
            extract($options);

        $org_select = '';
        if ($userType === BaseDataModel::JOB) {
            $org_select = ", organization_cached_profile.data->'profileData'->>'organization_name' as org_name";
            $org_select .= ", job_cached_profile.updated_at as job_updated";
        }

        $left_join_id = $this->securityContext()->getToken()->currentUser()->id;
        if (isset($job_owner)) {
            $left_join_id = $job_owner;
        }
        if (isset($job_owner) && isset($user_id) && $job_owner != $user_id) {
            $left_join_id = $user_id;
        }

        $distinct = '';
        if (isset($sort_by) && isset($order_by)) {
            switch ($sort_by) {
                case 'name':
                    $distinct = ','.DB::raw("(matching_results.data->'profileData'->>'first_name')::text");
                    break;
                case 'last_login':
                    $distinct = ','.DB::raw("(matching_results.data->>'last_login')::text");
                    break;
                case 'birth_date':
                    $distinct = ','.DB::raw("(matching_results.data->'profileData'->>'birth_year')::int");
                    break;
            }
        }

        $builder =
            $this->getModel()
                 ->select(DB::raw("distinct on (matching_results.user_type, matching_results.user_id, matching_results.for_user_id, matching_results.for_user_type, matching_results.matching_percentage$distinct) matching_results.data, folder_apps.folder, notes.notes, favorite.id as favorite, subscriptions.partnership, subscriptions.id as sub_id $org_select") )
                 ->leftJoin("folder_apps", function($join) use ($forUserId, $forUserType, $userType, $left_join_id){
                    $join->on("folder_apps.user_id", "=", "matching_results.user_id")
                         ->where("matching_results.for_user_type", "=", $forUserType)
                         ->where("folder_apps.for_user_id", "=", $left_join_id)
                         ->where("folder_apps.user_type", "=", $userType)
                         ->where("folder_apps.app_id", "=", $this->securityContext()->getApp()->id());
                 })
                ->leftJoin("notes", function($join) use ($forUserId, $forUserType, $userType, $left_join_id){
                    $join->on("notes.user_id", "=", "matching_results.user_id")
                        ->where("notes.for_user_id", "=", $left_join_id)
                        ->where("notes.user_type", "=", $userType);
                })
                ->leftJoin("favorite", function($join) use ($forUserId, $forUserType, $userType, $left_join_id){
                    $join->on("favorite.target_id", "=", "matching_results.user_id")
                        ->where("favorite.user_id", "=", $left_join_id)
                        ->where("favorite.target_type", "=", $userType)
                        ->where("favorite.app_id", "=", $this->securityContext()->getApp()->id());
                })
                 ->where("matching_results.for_user_type","=", $forUserType)
                 ->where("matching_results.user_type", "=", $userType)
                 ->where("matching_results.for_user_id", "=",  $forUserId)
                 ->where("matching_results.app_id", "=",  SecurityContext::getInstance()->getApp()->id);

            if(isset($updates)) {
                $updates .= '-01-01 00:00:00';
                $builder->leftJoin("users", "users.id", "=", 'matching_results.user_id')
                    ->where('users.last_login', '>=', $updates);
            }

            if ($userType === BaseDataModel::JOB ) {
                $builder->leftJoin("organization_cached_profile", "organization_cached_profile.id", "=",  DB::raw("(matching_results.data->'organization'->>'id')::int"));
                $builder->leftJoin("job_cached_profile", "job_cached_profile.id", "=",  DB::raw("(matching_results.data->>'id')::int"));
            }

            $builder = $userType === BaseDataModel::JOB ? $builder->leftJoin("subscriptions", "subscriptions.user_id", "=",  DB::raw("(matching_results.data->'organization'->>'id')::int"))
                                                     : $builder->leftJoin("subscriptions", "subscriptions.user_id", "=",  DB::raw("(matching_results.data->>'id')::int"));

            $builder->where('subscriptions.app_id', '=', $this->securityContext()->getApp()->id() )
                ->where('subscriptions.status', '<>', Subscription::STATUS_CLOSED)
                ->whereNotNull('subscriptions.id')
                ->where(function($query){
                    $query->where('subscriptions.status', '<>', Subscription::STATUS_EXPIRED)
                          ->orWhere('subscriptions.price', "=", 0);

                });

            if(isset($rate))
                $builder->where('matching_results.matching_percentage', '>=', $rate);

        if ($userType === BaseDataModel::JOB) {
            $date_limit = new \DateTime('now');
            $date_limit->modify("-6 months");
            $timelimit = $date_limit->getTimestamp();

            $builder->where(DB::raw("(job_cached_profile.data->>'updated_at')::date"), '>=', date('Y-m-d', $timelimit));
        }

            if (isset($sort_by) && isset($order_by)) {
                switch ($sort_by) {
                    case 'matching_percentage':
                        $builder->orderBy('matching_results.matching_percentage', $order_by);
                        break;
                    case 'name':
                        $builder->orderBy(DB::raw("(matching_results.data->'profileData'->>'first_name')::text"), $order_by);
                        break;
                    case 'last_login':
                        $builder->orderBy(DB::raw("(matching_results.data->>'last_login')::text"), $order_by);
                        break;
                    case 'birth_date':
                        $builder->orderBy(DB::raw("(matching_results.data->'profileData'->>'birth_year')::int"), $order_by);
                        break;
                    default:
                        $builder->orderBy('matching_results.matching_percentage', 'desc');
                        break;
                }
            } else {
                $builder->orderBy('matching_results.matching_percentage', 'desc');
            }


            $result = (new UserCachedTransformer($builder, new UserCachedDataStrategy()))->paginate(static::PAGINATION);

         return (new TransData($this->securityContext()->getToken()->language(), $userType, $result->toArray()))->get();

    }

    /**
     * @param $forUserId
     * @param $userId
     * @param $role
     *
     * @return array
     */
    public function oneMatchingResult($forUserId, $userId, $role)
    {
        $builder = $this->getModel()
            ->select(DB::raw("distinct on (matching_results.user_type, matching_results.user_id, matching_results.for_user_id, matching_results.for_user_type, matching_results.matching_percentage) matching_results.data"))
            ->where('matching_results.user_id', $userId)
            ->where('matching_results.for_user_id', $forUserId);

        $result = (new UserCachedTransformer($builder, new UserCachedDataStrategy()))->paginate(static::PAGINATION);

        return (new TransData($this->securityContext()->getToken()->language(), $role, $result->toArray()))->get();

    }
} 
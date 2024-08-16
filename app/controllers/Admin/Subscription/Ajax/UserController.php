<?php


namespace MissionNext\Controllers\Admin\Subscription\Ajax;


use Illuminate\Mail\Message;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use MissionNext\Controllers\Admin\AdminBaseController;
use MissionNext\Helpers\Language;
use MissionNext\Models\Application\Application;
use MissionNext\Models\CacheData\UserCachedData;
use MissionNext\Models\Language\LanguageModel;
use MissionNext\Models\Matching\Results;
use MissionNext\Models\Subscription\Subscription;
use MissionNext\Models\User\ExtendedUser;
use MissionNext\Models\User\User;
use MissionNext\Repos\CachedData\UserCachedRepository;
use MissionNext\Repos\CachedData\UserCachedRepositoryInterface;
use MissionNext\Repos\User\ProfileRepositoryFactory;
use MissionNext\Repos\User\UserRepository;
use SparkPost\SparkPost;
use GuzzleHttp\Client;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;

class UserController extends AdminBaseController
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList()
    {
        $filters = $this->request->query->get('filters');
        $sorting = $this->request->query->get('sort');

        $profileFilter = isset($filters['profile']) ? $filters['profile'] : null;
        $appFilter = isset($filters['app']) ? $filters['app'] : null;
        $roleFilter = isset($filters['role']) ? $filters['role'] : null;
        $statusFilter = isset($filters['status']) ? $filters['status'] : null;
        $subStatusFilter = isset($filters['sub_status']) ? $filters['sub_status'] : null;



        if ($appFilter){
            $appFilter = explode('|',$appFilter);
        }

        if ($roleFilter){
            $roleFilter = explode('|',$roleFilter);
        }

        if ($statusFilter){
            $statusFilter = explode('|',$statusFilter);
        }

        if ($subStatusFilter){
            $subStatusFilter = explode('|',$subStatusFilter);
        }


        $usersQuery =   ExtendedUser::query();

        $usersQuery = $usersQuery->select(DB::raw("distinct on (users.id, users.created_at, users.username, users.last_login)   users.*") )
                            ->leftJoin('subscriptions','subscriptions.user_id', '=', 'users.id');

        $usersQuery = $subStatusFilter ?  $usersQuery->whereIn('subscriptions.status', $subStatusFilter) : $usersQuery;

        $usersQuery = $profileFilter ? $usersQuery->where( function($query) use ($profileFilter){
            $query->where('username', 'LIKE', '%'.$profileFilter.'%' )
                ->orWhere('email', 'LIKE', '%'.$profileFilter.'%')
                ->orwhere('username', 'ilike', '%'.strtolower($profileFilter).'%' )
                ->orWhere('email', 'ilike', '%'.strtolower($profileFilter).'%');
        }) : $usersQuery;

        $usersQuery = $appFilter ?  $usersQuery->leftJoin('user_apps', 'user_apps.user_id','=', 'users.id')
                                       //->leftJoin('application', 'application.id','=', 'user_apps.app_id')

            ->whereIn('user_apps.app_id', $appFilter) : $usersQuery;

        $usersQuery = $roleFilter ?  $usersQuery->leftJoin('user_roles', 'user_roles.user_id','=', 'users.id')
            ->whereIn('user_roles.role_id', $roleFilter) : $usersQuery;

        $usersQuery = $statusFilter ?  $usersQuery->where(function($query) use ($statusFilter){
            $customQuery = null;
            if (in_array(1, $statusFilter)){
                $customQuery =  $query->where(function($sq){
                    $sq->where('users.status', '=', 1)
                       ->where('users.is_active', '=', false);

                });
            }
            if (in_array(2, $statusFilter)){
                if ($customQuery){
                    $customQuery = $query->orWhere(function($sq){
                        $sq->orWhere('users.status', '=', 0)
                           ->where('users.is_active', '=', true);
                    });

                }else{
                    $customQuery = $query->where(function($sq){
                        $sq->where('users.status', '=', 0)
                            ->where('users.is_active', '=', true);
                    });
                }
            }
            if (in_array(3, $statusFilter)){
                if ($customQuery){
                    $customQuery = $query->orWhere(function($sq){
                        $sq->orWhere('users.status', '=', 0)
                            ->where('users.is_active', '=', false);
                    });

                }else{
                    $customQuery = $query->where(function($sq){
                        $sq->where('users.status', '=', 0)
                            ->where('users.is_active', '=', false);
                    });
                }
            }

        }) : $usersQuery;


        $totalCount = $usersQuery->get()->count();
        $users =  $usersQuery->with(['subscriptions'=> function($q){
            $q->where('status', '<>', Subscription::STATUS_CLOSED );
        } ] )->orderBy(DB::raw("users.".$sorting['p'] ), $sorting['o'])->paginate(static::PAGINATE);

        $users->each(function($user){

            $user->general_status = Subscription::STATUS_ACTIVE;
           foreach($user->subscriptions as $subscription){
               if ($subscription->status === Subscription::STATUS_EXPIRED){
                    $user->general_status = Subscription::STATUS_EXPIRED;
                    break;
               }elseif($subscription->status === Subscription::STATUS_GRACE){
                   $user->general_status = Subscription::STATUS_GRACE;
               }
           }
        });

       return Response::json(["users" => $users->toArray(), 'totalUsers' => $totalCount, 'itemsPerPage' => static::PAGINATE ]);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndex($id)
    {
        /** @var  $repo UserCachedRepository */
        $repo = $this->repoContainer[UserCachedRepositoryInterface::KEY];
        $repo->findOrFail($id);

        return Response::json(["user" => $repo->getModel()->getData()]);
    }

    /**
     * @param $isActive
     * @param $userId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setStatus($isActive, $userId)
    {
        $user = User::findOrFail($userId);
        $isActive = $isActive  === 'enable' ? true : false;
        $user->is_active = $isActive;
        $user->status = 0;
        $user->save();

        $to_name = $user->username;
        $to_email = $user->email;

        Mail::send('admin.mail.user.status', ['user' => $user->toArray()], function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)->subject('Your access was changed');
            $message->from('do_not_reply@missionnext.org', 'MissionNext Staff',);
        });

        /** @var  $userRepo UserRepository */
        $userRepo = $this->repoContainer[ProfileRepositoryFactory::KEY]->profileRepository();
        $userRepo->addUserCachedData($user);

        return Response::json(["is_active" => $isActive, "status" => 0 ]);
    }

    /**
     * @param $isActive
     * @param $userId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setAppStatus($isActive, $userId, $appId)
    {
        $isActive = $isActive  === 'enable' ? true : false;

        if (!$isActive) {
            $user = User::find($userId);
            $jobs = $user->jobs()->where('app_id', $appId)->get();
            foreach ($jobs as $jobItem) {
                Results::where('user_type', 'job')
                    ->where('user_id', $jobItem->id)
                    ->orWhere('for_user_type', 'job')
                    ->where('for_user_id', $jobItem->id)->delete();
            }
            Results::where('app_id', $appId)
                ->where('user_type', $user->role())
                ->where('user_id', $user->id)
                ->orWhere('for_user_type', $user->role())
                ->where('for_user_id', $user->id)
                ->where('app_id', $appId)->delete();
        }

        User::find($userId)->appsStatuses()->updateExistingPivot($appId, ['is_active' => $isActive]);

        return Response::json(["is_active" =>  User::find($userId)->isActiveInApp(Application::find($appId)) ]);
    }
} 
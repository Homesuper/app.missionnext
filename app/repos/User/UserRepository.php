<?php
namespace MissionNext\Repos\User;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use MissionNext\Api\Exceptions\UserException;
use MissionNext\Api\Service\DataTransformers\UserCachedDataStrategy;
use MissionNext\Api\Service\DataTransformers\UserCachedTransformer;
use MissionNext\Models\Application\Application;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\Language\LanguageModel;
use MissionNext\Models\Profile;
use MissionNext\Models\ProfileInterface;
use MissionNext\Models\User\User;
use MissionNext\Repos\Field\Field;
use MissionNext\Repos\Field\FieldRepository;
use MissionNext\Repos\Field\FieldRepositoryInterface;


class UserRepository extends AbstractUserRepository implements UserRepositoryInterface
{

    /**
     * @param ProfileInterface $user
     * @param LanguageModel $languageModel
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function profileDataTransQuery(ProfileInterface $user, LanguageModel $languageModel)
    {
        $this->model = $user;
        $this->languageModel = $languageModel;
        /** @var  $fieldRepo FieldRepository */
        $fieldRepo = $this->repoContainer[FieldRepositoryInterface::KEY];
        $secContext = $this->repoContainer->securityContext();
        $role = $secContext->role();
        //dd($this->repoContainer);
        $builder =  $fieldRepo->getModel()
            ->leftJoin("{$role}_profile", "{$role}_fields.id", "=", "{$role}_profile.field_id" )

            ->leftJoin("{$role}_dictionary_trans", function($join) use ($role, $languageModel){
                $join->on("{$role}_profile.dictionary_id", "=", "{$role}_dictionary_trans.dictionary_id")
                    ->where("{$role}_dictionary_trans.lang_id", "=", $languageModel->id);
            })
            ->leftJoin("{$role}_fields_trans", function($join) use ($role, $languageModel){
                $join->on("{$role}_fields_trans.field_id", "=", "{$role}_fields.id")
                    ->where("{$role}_fields_trans.lang_id", "=", $languageModel->id);
            })
            ->leftJoin("users", "users.id", "=", "{$role}_profile.user_id")
            ->leftJoin("user_roles", "user_roles.user_id", "=", "{$role}_profile.user_id")
            ->leftJoin("roles", "roles.id", "=", "user_roles.role_id")
            ->where("{$role}_profile.user_id", "=", $user->id)

            ->select(
                'users.id',
                'users.username',
                'users.email',
                'users.created_at',
                'users.updated_at',
                'users.last_login',
                'roles.role as role',
                "{$role}_dictionary_trans.value as trans_value",
                "{$role}_profile.value",
                "{$role}_dictionary_trans.lang_id",
                "{$role}_fields.id as field_id",
                "{$role}_fields.type",
                "{$role}_fields.symbol_key",
                "{$role}_fields.name",
                "{$role}_fields_trans.name as trans_name"
            );
        if (!$languageModel->id) {

            $builder->addSelect("{$role}_profile.value as trans_value");
        }
//        }else{
//            dd($builder->get()->toArray());
//        }

        return $builder;
    }


    protected $modelClassName = User::class;

    /**
     * @return User
     */
    public function getModel()
    {

        return $this->model;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function setModel(User $user)
    {
        $this->model = $user;

        return $this;
    }

    /**
     * @param Application $app
     *
     * @return bool
     */
    public function addApp(Application $app)
    {
        if (!$this->getModel()->hasApp($app)) {
            $this->getModel()->apps()->attach($app->id);

            return true;
        }

        return false;
    }

    /**
     * @param User $organization
     * @param User $user
     *
     * @return Collection
     */
    public function organizationJobsForUser(User $organization, User $user)
    {
        /** @var  $jobRepo JobRepository */
        $jobRepo = $this->repoContainer[JobRepositoryInterface::KEY];

        $date_limit = new \DateTime('now');
        $date_limit->modify("-6 months");
        $timelimit = $date_limit->getTimestamp();

        $builder =  $jobRepo->getModel()
                     ->select("job_cached_profile_trans.data", "notes.notes", "folder_apps.folder")
                    ->where(DB::raw("(job_cached_profile_trans.data->>'updated_at')::date"), '>=', date('Y-m-d', $timelimit))
                     ->leftJoin("job_cached_profile_trans", "job_cached_profile_trans.id", "=", "jobs.id")
                     ->leftJoin("folder_apps", function($join) use ($user){
                            $join->on("folder_apps.user_id", "=", "jobs.id")
                                ->where("folder_apps.for_user_id", "=", $user->id)
                                ->where("folder_apps.user_type", "=", BaseDataModel::JOB)
                                ->where("folder_apps.app_id", "=", $this->securityContext->getApp()->id());


                    })
                    ->leftJoin("notes", function($join) use ($user){
                              $join->on("notes.user_id", "=", "jobs.id")
                                    ->where("notes.for_user_id", "=", $user->id)
                                    ->where("notes.user_type", "=", BaseDataModel::JOB);
                    })
                     ->where("jobs.organization_id", "=", $organization->id)
                     ->where("jobs.app_id", "=", $this->securityContext->getApp()->id())
                     ->where("job_cached_profile_trans.lang_id", "=", $this->securityContext->getToken()->language()->id);

        return
            (new UserCachedTransformer($builder, new UserCachedDataStrategy()))->get();

    }



    public function setUsersBaseData(Profile $profile, ProfileInterface $data)
    {
        $profile->id = $data->id;
        $profile->username = $data->username;
        $profile->role = $data->role();
        $profile->is_active = $data->is_active;
        $profile->status = $data->status;
        $profile->email = $data->email;
        $profile->created_at = $data->created_at;
        $profile->updated_at = $data->updated_at;
        $profile->last_login = $data->last_login;
    }

} 
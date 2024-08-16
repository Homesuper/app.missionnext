<?php

namespace MissionNext\Repos\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use MissionNext\Models\Application\Application;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\Job\Job;
use MissionNext\Models\Language\LanguageModel;
use MissionNext\Models\Profile;
use MissionNext\Models\ProfileInterface;
use MissionNext\Repos\Field\FieldRepository;
use MissionNext\Repos\Field\FieldRepositoryInterface;


class JobRepository extends AbstractUserRepository implements JobRepositoryInterface
{
    protected $modelClassName = Job::class;

    /**
     * @return Job
     */
    public function getModel()
    {

        return $this->model;
    }

    /**
     * @param Application $app
     *
     * @return bool
     */
    public function addApp(Application $app)
    {
        if (!$this->getModel()->hasApp($app)){
            $this->getModel()->app_id = $app->id;

            return true;
        }

        return false;
    }

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
        $builder =   $fieldRepo->getModel()
            ->leftJoin("{$role}_profile", "{$role}_fields.id", "=", "{$role}_profile.field_id" )

            ->leftJoin("{$role}_dictionary_trans", function($join) use ($role, $languageModel){
                $join->on("{$role}_profile.dictionary_id", "=", "{$role}_dictionary_trans.dictionary_id")
                    ->where("{$role}_dictionary_trans.lang_id", "=", $languageModel->id);
            })
            ->leftJoin("{$role}_fields_trans", function($join) use ($role, $languageModel){
                $join->on("{$role}_fields_trans.field_id", "=", "{$role}_fields.id")
                    ->where("{$role}_fields_trans.lang_id", "=", $languageModel->id);
            })
            ->leftJoin("jobs", "jobs.id", "=", "{$role}_profile.job_id")
            ->leftJoin("users", "jobs.organization_id", "=", "users.id" )
            ->where("{$role}_profile.job_id", "=", $user->id)

            ->select(
                'jobs.id',
                'jobs.name',
                'users.username as org_username',
                'users.email as org_email',
                'users.id as org_id',
                'jobs.created_at',
                'jobs.updated_at',
                "{$role}_dictionary_trans.value as trans_value",
                "{$role}_profile.value",
                "{$role}_dictionary_trans.lang_id",
                "{$role}_fields.id as field_id",
                "{$role}_fields.type",
                "{$role}_fields.symbol_key",
                "{$role}_fields.name",
                "{$role}_fields_trans.name as trans_name"
            );

        if (!$languageModel->id){

            $builder->addSelect("{$role}_profile.value as trans_value");
        }

        return $builder;
    }






    public function setUsersBaseData(Profile $profile, ProfileInterface $data)
    {
        $profile->id = $data->id;
        $profile->name = $data->name;
        $profile->role = $data->role();
        $profile->app_id = $data->app_id;
        $profile->organization = $data->organization->toArray();
        $profile->organization_id = $data->organization_id;
        $profile->created_at = $data->created_at;
        $profile->updated_at = $data->updated_at;
    }
}
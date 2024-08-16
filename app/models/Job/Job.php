<?php
namespace MissionNext\Models\Job;

use Illuminate\Support\Facades\App;
use MissionNext\Models\Application\Application;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\ModelInterface;
use MissionNext\Models\ModelObservable;
use MissionNext\Models\Observers\UserObserver;
use MissionNext\Models\ProfileInterface;
use MissionNext\Models\User\User as UserModel;
use MissionNext\Repos\RepositoryContainerInterface;
use MissionNext\Repos\User\JobRepository;
use MissionNext\Repos\User\JobRepositoryInterface;
use MissionNext\Repos\User\UserRepositoryInterface;

class Job extends ModelObservable implements ProfileInterface
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'jobs';

    protected $fillable = array('name', 'symbol_key');

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function jobFields()
    {

        return $this->belongsToMany(JobField::class, 'job_profile', 'job_id', 'field_id')->withPivot('value');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {

      return $this->belongsTo(UserModel::class, 'organization_id', 'id');
    }

    /**
     * @param $symbolKey
     *
     * @return $this
     */
    public function setSymbolKey($symbolKey)
    {
        $this->symbol_key = $symbolKey;

        return $this;
    }

    public function appData()
    {

        return $this->app()->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function app()
    {

        return $this->belongsTo(Application::class, 'app_id', 'id');
    }

    /**
     * @param UserModel $organization
     *
     * @return $this
     */
    public function setOrganization(UserModel $organization)
    {
        $this->organization()->associate($organization);

        return $this;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function appIds()
    {

        return [$this->app_id];
    }

    /**
     * @param Application $app
     *
     * @return bool
     */
    public function hasApp(Application $app)
    {

        return (bool)$this->app_id;
    }

    public function hasRole($check)
    {

        return $check === BaseDataModel::JOB;
    }

    /**
     * @return string
     */
    public function role()
    {

        return BaseDataModel::JOB;
    }

    /**
     * @param Application $app
     *
     * @return bool
     */
    public function addApp(Application $app)
    {
        if (!$this->hasApp($app)){
            $this->app_id = $app->id;

            return true;
        }

        return false;
    }

    /**
     * @return JobRepository
     */
    public function getRepo()
    {

        $repoContainer = App::make(RepositoryContainerInterface::class);

        return $repoContainer[JobRepositoryInterface::KEY];
    }

} 
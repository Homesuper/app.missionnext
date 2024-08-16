<?php


namespace MissionNext\Models\Inquire;

use Illuminate\Database\Eloquent\Model as Eloquent;
use MissionNext\Api\Auth\SecurityContext;
use MissionNext\Models\Application\Application;
use MissionNext\Models\Job\Job;
use MissionNext\Models\ModelInterface;
use MissionNext\Models\User\User as UserModel;
use MissionNext\Facade\SecurityContext as Fsc;

class Inquire extends Eloquent implements ModelInterface
{
    const STATUS_INQUIRED = 'inquired';
    const STATUS_DELETED = 'deleted';


    protected $table = "inquires";

    protected $fillable = array('candidate_id', 'job_id', 'status', 'app_id');




    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function candidate()
    {

        return $this->belongsTo(UserModel::class, 'candidate_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function job()
    {

        return $this->belongsTo(Job::class, 'job_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function application()
    {

        return $this->belongsTo(Application::class, 'app_id', 'id');
    }
} 
<?php


namespace MissionNext\Models\Configs;


use Illuminate\Database\Eloquent\Model;
use MissionNext\Models\Application\Application;
use MissionNext\Models\User\User;

class UserConfigs extends Model
{
    protected $table = 'user_apps_config';

    public $timestamps = false;

    protected $fillable = ['key', 'value', 'app_id', 'user_id'];


    public function app()
    {

        return $this->belongsTo(Application::class, 'app_id', 'id');
    }

    public function user()
    {

        return $this->belongsTo(User::class, 'user_id', 'id');
    }
} 
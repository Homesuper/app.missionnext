<?php


namespace MissionNext\Models\Configs;


use Illuminate\Database\Eloquent\Model;
use MissionNext\Models\Application\Application;

class AppConfigs extends Model
{
    protected $table = 'app_config';

    public $timestamps = false;

    protected $fillable = ['key', 'value', 'app_id'];


    public function app()
    {

        return $this->belongsTo(Application::class, 'app_id', 'id');
    }
} 
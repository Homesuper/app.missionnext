<?php
namespace MissionNext\Models\Application;


use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model as Eloquent;
use MissionNext\Facade\SecurityContext;
use MissionNext\Models\Configs\AppConfigs;
use MissionNext\Models\DataModel\AppDataModel;
use MissionNext\Models\Language\LanguageModel;
use MissionNext\Models\ModelInterface;
use MissionNext\Models\Observers\ApplicationObserver;
use MissionNext\Models\Subscription\SubConfig;
use MissionNext\Models\User\User;


class Application extends Eloquent implements ModelInterface
{
    protected $table = 'application';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('private_key');

    /** @var array protected from mass assignment */
    protected $guarded = array('public_key', 'private_key');

    protected $fillable = array('name');

    protected static function boot()
    {
        parent::boot();
        parent::observe(new ApplicationObserver());
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function languages()
    {

        return $this->belongsToMany(LanguageModel::class, 'app_languages', 'app_id', 'lang_id');
    }

    /**
     * @return HasMany
     */
    public function dataModels()
    {

        return $this->hasMany(AppDataModel::class, 'app_id', 'id');
    }

    public function id()
    {

        return $this->id;
    }

    /**
     * @param null $type
     * @return AppDataModel
     */
    public function DM($type = null)
    {
        $type = $type ?: SecurityContext::role();

        return
            $this->dataModels()->whereType($type)->firstOrFail();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {

        return $this->belongsToMany(User::class, 'user_apps', 'app_id', 'user_id');
    }

    /**
     * @return HasMany
     */
    public function configs()
    {

        return $this->hasMany(AppConfigs::class, 'app_id');
    }

    /**
     * @return HasMany
     */
    public function subConfigs()
    {

        return $this->hasMany(SubConfig::class, 'app_id');
    }


} 
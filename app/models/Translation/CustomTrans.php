<?php

namespace MissionNext\Models\Translation;


use Illuminate\Database\Eloquent\Model;
use MissionNext\Models\ModelInterface;

class CustomTrans extends Model implements ModelInterface
{

    protected $table = 'custom_trans';

    public $timestamps = false;

    protected $fillable = array('key', 'lang_id', 'app_id', 'value');

    /**
     * @param \ArrayObject $transObject
     *
     * @return boolean
     */
    public function insertTransData(\ArrayObject $transObject)
    {

       return $this->insert(
            [
                'key' => $transObject->key,
                'app_id' => $transObject->app_id,
                'value' => $transObject->value,
                'lang_id' => $transObject->lang_id,

            ]
        );
    }

    /**
     * @param \ArrayObject $transObject
     *
     * @return boolean
     */
    public function updateTransData(\ArrayObject $transObject)
    {

       return $this->whereAppId($transObject->app_id)
            ->whereKey($transObject->key)
            ->whereLangId($transObject->lang_id)
            ->update(['value' => $transObject->value]);
    }

} 
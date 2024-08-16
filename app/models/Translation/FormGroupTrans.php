<?php


namespace MissionNext\Models\Translation;


use Illuminate\Database\Eloquent\Model;
use MissionNext\Models\ModelInterface;

class FormGroupTrans extends Model implements ModelInterface
{

    protected $table = 'form_groups_trans';

    public $timestamps = false;

    protected $fillable = array('lang_id', 'app_id', 'group_id', 'value');

    /**
     * @param \ArrayObject $transObject
     *
     * @return boolean
     */
    public function insertTransData(\ArrayObject $transObject)
    {

        return $this->insert(
            [
                'group_id' => $transObject->group_id,
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
            ->whereGroupId($transObject->group_id)
            ->whereLangId($transObject->lang_id)
            ->update(['value' => $transObject->value]);
    }

} 
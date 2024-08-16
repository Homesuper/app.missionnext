<?php

namespace MissionNext\Api\Service\ResponseDataFormat;


use Illuminate\Database\Eloquent\Model;

class FieldChoices extends Model
{

    protected $fillable = ['value', 'default_value', 'id', 'order', 'dictionary_order', 'dictionary_meta'];

    #protected  $value, $default_value, $id, $order, $dictionary_order;


    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param mixed $default_value
     */
    public function setDefaultValue($default_value)
    {
        $this->default_value = $default_value;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @param mixed $dictionary_order
     */
    public function setDictionaryOrder($dictionary_order)
    {
        $this->dictionary_order = $dictionary_order;
    }

    /**
     * @param $meta
     */
    public function setMeta($meta)
    {
        $this->dictionary_meta = json_decode($meta, true);
    }

} 
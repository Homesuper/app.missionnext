<?php
namespace MissionNext\Models\Form;

use Illuminate\Database\Eloquent\Model as Eloquent;

class BaseForm extends Eloquent
{

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

    /**
     * @param $key
     *
     * @return $this
     */
    public function setSymbolKey($key)
    {
        $this->symbol_key = strtolower($key);

        return $this;
    }

    /**
     * @param $meta
     *
     * @return $this
     */
    public function setMeta($meta)
    {
        $this->meta = strtolower($meta);

        return $this;
    }

} 
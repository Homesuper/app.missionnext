<?php
namespace MissionNext\Models\Field;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Query\Builder;
use MissionNext\DB\SqlStatement\Sql;
use MissionNext\Models\DataModel\AppDataModel;
use MissionNext\Models\ModelInterface;

abstract class BaseField extends Eloquent
{

    public $timestamps = false;

    protected $roleType = null;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fields';


    protected $fillable = array('name',  'symbol_key', 'default_value', 'type', 'meta', 'note');


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {

        return $this->belongsTo(FieldType::class, 'type');
    }

    /**
     * @param array $meta
     *
     * @return $this
     */
    public function setMeta(array $meta)
    {
        $this->meta = json_encode($meta);

        return $this;
    }

    /**
     * @return array
     */
    public function getMeta()
    {

        return json_decode($this->meta, true);
    }


} 
<?php
namespace MissionNext\Models\Language;

use Illuminate\Database\Eloquent\Model;

class LanguageModel extends Model {

    protected $fillable = ['id', 'key', 'name'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'languages';

    public $timestamps = false;

} 
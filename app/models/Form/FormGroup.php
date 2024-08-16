<?php
namespace MissionNext\Models\Form;


use MissionNext\Models\ModelInterface;
use MissionNext\Models\Form\AppForm;
use MissionNext\Models\Field\FieldGroup;

class FormGroup extends BaseForm implements ModelInterface
{

    protected $table = 'form_groups';

    protected $fillable = array('symbol_key', 'name', 'order', 'meta');

    public function form()
    {

        return $this->belongsTo(AppForm::class);
    }

    public function fields(){

        return $this->hasMany(FieldGroup::class, 'group_id');
    }


} 
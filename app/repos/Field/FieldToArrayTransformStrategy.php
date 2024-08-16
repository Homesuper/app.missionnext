<?php

namespace MissionNext\Repos\Field;


use Illuminate\Support\Collection;
use MissionNext\Repos\TransformDataStrategy;

class FieldToArrayTransformStrategy extends TransformDataStrategy {
    /** @var  array */
    private $fields = [];


    /**
     * @param array $fields
     */
    public function __construct(array $fields)
    {
          $this->fields = $fields;
    }

    /**
     * @param \IteratorAggregate $dataCollection
     *
     * @return Collection
     */
    public function transform(\IteratorAggregate $dataCollection)
    {
        $this->dataCollection = $dataCollection;

        return $this->dataCollection->each(function ($field) {
          //  dd($field->toArray());
           foreach($this->fields as $fieldName){

               if (is_string($fieldName)) {
                   $field->{$fieldName} = //explode("|", $field->{$fieldName});
                       strlen($field->{$fieldName}) > 0  ? explode("|", $field->{$fieldName}) : null;
               }else{
                   $name = key($fieldName);
                   $field->{$name} =  json_decode($field->{$name}, true);
               }
           }

           return $field;
        });
    }

} 
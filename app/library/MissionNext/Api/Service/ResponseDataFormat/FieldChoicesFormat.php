<?php


namespace MissionNext\Api\Service\ResponseDataFormat;


use Illuminate\Support\Collection;

class FieldChoicesFormat extends AbstractFormat
{
    /**
     * @param Collection $collection
     * @return Collection
     */
    public static function format(Collection $collection)
    {
        $collection->each(function($el){
            if  (!empty($el->choices)){
		//var_dump($el->choices);
		//var_dump($el->dictionary_meta);
                $fieldChoiceCollection = new Collection();
                foreach($el->choices as $key=>$choice){
		    if ($el->dictionary_meta !== NULL) {
		    $choiceObject = new FieldChoices();
                    $choiceObject->setDefaultValue($choice);
                    $choiceObject->setMeta($el->dictionary_meta[$key]);
                    $choiceObject->setValue('');
                    $choiceObject->setId($el->dictionary_id[$key]);
                    $choiceObject->setDictionaryOrder($el->dictionary_order[$key]);
                    $fieldChoiceCollection->push($choiceObject);
		    }
                }
                $el->choices = $fieldChoiceCollection->toArray();
            }
            unset($el->dictionary_id);
            unset($el->dictionary_order);
            unset($el->dictionary_meta);
        });

        return $collection;
    }
} 

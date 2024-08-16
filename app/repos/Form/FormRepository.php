<?php

namespace MissionNext\Repos\Form;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use MissionNext\DB\SqlStatement\Sql;
use MissionNext\Models\Form\AppForm;
use MissionNext\Models\Translation\FormGroupTrans;
use MissionNext\Repos\AbstractRepository;
use MissionNext\Repos\Field\FieldDataTransformer;
use MissionNext\Repos\Field\FieldToArrayTransformStrategy;

class FormRepository extends AbstractRepository implements FormRepositoryInterface
{
    protected $modelClassName = AppForm::class;

    /**
     * @return AppForm
     */
    public function getModel()
    {

        return $this->model;
    }

    /**
     * @param Collection $collection
     *
     * @return array
     */
    private function structureData(Collection $collection)
    {
        $structuredData = [];
        foreach($collection as $groupField){
            $groupId = $groupField->id;
            $fieldId = $groupField->field_id;
            $structuredData[$groupId]['symbol_key'] = $groupField->symbol_key;
            $structuredData[$groupId]['id'] = $groupId;
            $structuredData[$groupId]['name'] = $groupField->name;
            $structuredData[$groupId]['depends_on'] = $groupField->depends_on;
            $structuredData[$groupId]['depends_on_option'] = $groupField->depends_on_option;
            $structuredData[$groupId]['is_outer_dependent'] = $groupField->is_outer_dependent;
            $structuredData[$groupId]['order'] = $groupField->order;
            $structuredData[$groupId]['meta'] = $groupField->group_meta ? json_decode($groupField->group_meta, true) : [];

            $choices = $groupField->field_choices ? : [];
            $defChoices = $groupField->field_default_choices ? : [];
            $choicesIds = $groupField->field_default_dictionary_id ? : [];
            $dictionaryOrder = $groupField->dictionary_order ? : [];
            $dictionaryMeta = $groupField->dictionary_meta ? : [];

            $structuredData[$groupId]['fields'][$fieldId]['id'] = $fieldId;
            $structuredData[$groupId]['fields'][$fieldId]['symbol_key'] = $groupField->field_symbol_key;
            $structuredData[$groupId]['fields'][$fieldId]['constraints'] = $groupField->field_constraints;
            $structuredData[$groupId]['fields'][$fieldId]['type'] = $groupField->field_type;
            $structuredData[$groupId]['fields'][$fieldId]['name'] = $groupField->field_trans_name;
            $structuredData[$groupId]['fields'][$fieldId]['default_name'] = $groupField->field_name;
            $structuredData[$groupId]['fields'][$fieldId]['default_note'] = $groupField->field_note;
            $structuredData[$groupId]['fields'][$fieldId]['note'] = $groupField->field_trans_note;
            //$structuredData[$groupId]['fields'][$fieldId]['choices'] = $choices;
            //$structuredData[$groupId]['fields'][$fieldId]['field_dictionary_ids'] = $groupField->field_dictionary_id ? : [];
            //  $structuredData[$groupId]['fields'][$fieldId]['default_choices'] = $defChoices;
            // $structuredData[$groupId]['fields'][$fieldId]['field_default_dictionary_ids'] = $groupField->field_default_dictionary_id ? : [];
            $structuredData[$groupId]['fields'][$fieldId]['default_value'] = $groupField->field_default_value;
            $structuredData[$groupId]['fields'][$fieldId]['order'] = $groupField->field_order;
            $structuredData[$groupId]['fields'][$fieldId]['meta'] = json_decode($groupField->field_meta, true);
            $structuredData[$groupId]['fields'][$fieldId]['model_meta'] = json_decode($groupField->model_field_meta, true);

            $dictionary = [];


            foreach($defChoices as $key => $defChoice){
                $dictionary[$key]['value'] = isset($choices[$key]) ? $choices[$key] : null;
                $dictionary[$key]['default_value'] = $defChoice;
                $dictionary[$key]['id'] = intval($choicesIds[$key]);
                $dictionary[$key]['dictionary_order'] = intval($dictionaryOrder[$key]);
                $dictionary[$key]['dictionary_meta'] = isset($dictionaryMeta[$key]) ? json_decode($dictionaryMeta[$key], true) : [];
            }

            $structuredData[$groupId]['fields'][$fieldId]['choices'] = $dictionary;

            //@TODO default_value to array
        }


        return $this->transGroupLabels($structuredData);
    }

    /**
     * @param AppForm $form
     *
     * @return FieldDataTransformer
     */
    public function groupedFields(AppForm $form)
    {
        $type = $this->repoContainer->securityContext()->role();
        $language = $this->repoContainer->securityContext()->getToken()->language();
        $dm = $this->repoContainer->securityContext()->getApp()->DM();

        $builder = $form
            ->leftJoin("form_groups", "form_groups.form_id","=", "app_forms.id")
            ->leftJoin("group_fields", "group_fields.group_id", "=", "form_groups.id")
            ->leftJoin($type."_fields", "group_fields.symbol_key", "=", $type."_fields.symbol_key")
            ->leftJoin('data_model_' . $type . '_fields', $type . '_fields.id', '=', 'data_model_' . $type . '_fields.field_id')
            ->leftJoin('field_types', 'field_types.id', '=', $type . '_fields.type')
            ->leftJoin($type . '_dictionary', $type . '_dictionary.field_id', '=', $type . '_fields.id')
            ->leftJoin($type . '_fields_trans', function ($join) use ($type, $language ) {
                $join->on($type . '_fields_trans.field_id', '=', $type . '_fields.id')
                    ->where($type . '_fields_trans.lang_id', '=', $language->id);
            })
            ->leftJoin($type . '_dictionary_trans', function ($join) use ($type, $language) {
                $join->on($type . '_dictionary_trans.dictionary_id', '=', $type . '_dictionary.id')
                    ->where($type . '_dictionary_trans.lang_id', '=', $language->id);
            })
            ->where('data_model_' . $type . '_fields.data_model_id', '=', $dm->id)
            ->where("form_groups.form_id", "=", $form->id)
            ->groupBy('field_trans_name',
                      'field_trans_note',
                      $type . '_fields.id',
                      'field_types.name',
                'data_model_' . $type . '_fields.constraints',
                'form_groups.symbol_key',
                'form_groups.id',
                'field_order',
                'field_meta'
            )

            ->select(
                'field_types.name as field_type',
                $type . '_fields_trans.name as field_trans_name',
                $type . '_fields_trans.note as field_trans_note',
                $type . '_fields.name as field_name',
                $type . '_fields.note as field_note',
                DB::raw("({$type}_fields.meta)::text as model_field_meta"),
                $type . '_fields.default_value as field_default_value',

                DB::raw(Sql::getDbStatement()->groupConcat("{$type}_dictionary_trans.value", "field_choices", "{$type}_dictionary_trans.dictionary_id" )),
                DB::raw(Sql::getDbStatement()->groupConcat("{$type}_dictionary.value", "field_default_choices", "{$type}_dictionary.id")),
                //DB::raw(Sql::getDbStatement()->groupConcat("{$type}_dictionary_trans.dictionary_id", "field_dictionary_id","{$type}_dictionary_trans.dictionary_id")),
                DB::raw(Sql::getDbStatement()->groupConcat("{$type}_dictionary.id", "field_default_dictionary_id","{$type}_dictionary.id")),
                DB::raw(Sql::getDbStatement()->groupConcat("{$type}_dictionary.order", "dictionary_order", "{$type}_dictionary.id")),
                DB::raw(Sql::getDbStatement()->groupConcat("{$type}_dictionary.meta", "dictionary_meta", "{$type}_dictionary.id")),
                $type.'_fields.symbol_key as field_symbol_key',
                'data_model_' . $type . '_fields.constraints as field_constraints',
                $type."_fields.id as field_id",
                'form_groups.symbol_key',
                'group_fields.order as field_order',
                DB::raw('(group_fields.meta)::text as field_meta'),
                'form_groups.id',
                'form_groups.name',
                DB::raw('(form_groups.meta)::text as group_meta'),
                'form_groups.depends_on',
                'form_groups.depends_on_option',
                'form_groups.is_outer_dependent',
                'form_groups.order'
            );


        return
            new FieldDataTransformer($builder, new FieldToArrayTransformStrategy(['field_choices', 'field_default_choices', 'field_default_value', 'field_dictionary_id', 'field_default_dictionary_id', 'dictionary_order', 'dictionary_meta']));

    }

    private function transGroupLabels(array $groups)
    {
        $secContext = $this->repoContainer->securityContext();

        $ids = array_fetch($groups, 'id') ? : [0];

        $groupTrans =  FormGroupTrans::whereIn('group_id', $ids)
            ->whereAppId($secContext->getApp()->id())
            ->whereLangId($secContext->getToken()->language()->id)
            ->get();

        $groupTrans->each(function($t) use (&$groups){
             foreach($groups as &$group){
                 if ($group['id'] == $t->group_id ){
                     $group['name'] = $t->value;
                 }
             }
        });

        return $groups;
    }

    /**
     * @param AppForm $form
     *
     * @return array
     */
    public function structuredGroupFields(AppForm $form)
    {

        return $this->structureData($this->groupedFields($form)->get());
    }

} 
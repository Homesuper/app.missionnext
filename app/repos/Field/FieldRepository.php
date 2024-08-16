<?php
namespace MissionNext\Repos\Field;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use MissionNext\Api\Exceptions\FieldException;
use MissionNext\Api\Service\ResponseDataFormat\FieldChoices;
use MissionNext\Api\Service\ResponseDataFormat\FieldChoicesFormat;
use MissionNext\DB\SqlStatement\Sql;
use MissionNext\Facade\SecurityContext;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\Dictionary\BaseDictionary;
use MissionNext\Models\Field\Candidate;
use MissionNext\Models\Language\LanguageModel;
use MissionNext\Models\ProfileInterface;
use MissionNext\Models\User\User as UserModel;
use MissionNext\Repos\ViewField\ViewFieldRepository;


class FieldRepository extends AbstractFieldRepository
{
    /**
     * @param LanguageModel $language
     *
     * @return FieldDataTransformer
     */
    public function fieldsExpandedTrans(LanguageModel $language)
    {
        $role = $this->repoContainer->securityContext()->role();

        if (!$language->id) {

            return $this->fieldsExpanded();
        }
        /**
         * @var $builder Builder
         */
        $builder = $this->getModel() //!!! getModel
        ->select($role . '_fields.id',
            'field_types.name as type',
            $role . '_fields.symbol_key',
            $role . '_fields.default_value',
            $role . '_fields_trans.name',
            $role . '_fields_trans.note',
            $role . '_fields.meta',
            DB::raw(Sql::getDbStatement()->groupConcat("{$role}_dictionary_trans.value", "choices", "{$role}_dictionary_trans.dictionary_id")),
            DB::raw(Sql::getDbStatement()->groupConcat("{$role}_dictionary_trans.dictionary_id", "dictionary_id", "{$role}_dictionary_trans.dictionary_id")),
            DB::raw(Sql::getDbStatement()->groupConcat("{$role}_dictionary.order", "dictionary_order", "{$role}_dictionary.id")))
            ->leftJoin('field_types', 'field_types.id', '=', $role . '_fields.type')
            ->leftJoin($role . '_dictionary', $role . '_dictionary.field_id', '=', $role . '_fields.id')
            ->leftJoin($role . '_fields_trans', function ($join) use ($role, $language) {
                $join->on($role . '_fields_trans.field_id', '=', $role . '_fields.id')
                    ->where($role . '_fields_trans.lang_id', '=', $language->id);
            })
            ->leftJoin($role . '_dictionary_trans', function ($join) use ($role, $language) {
                $join->on($role . '_dictionary_trans.dictionary_id', '=', $role . '_dictionary.id')
                    ->where($role . '_dictionary_trans.lang_id', '=', $language->id);
            })

            ->groupBy($role . '_fields.id', 'field_types.name', $role . '_fields_trans.name',  $role . '_fields_trans.note');

        return
            new FieldDataTransformer($builder, new FieldToArrayTransformStrategy(['choices', 'default_value', 'dictionary_id', ['meta' => 'json']]));

    }

    /**
     * @return FieldDataTransformer
     */
    public function fieldsExpanded()
    {
        $role = $this->repoContainer->securityContext()->role();
        /**
         * @var $builder Builder
         */
        $builder = $this->getModel() //!!! getModel
        ->select($role . '_fields.id',
            'field_types.name as type',
            $role . '_fields.symbol_key',
            $role . '_fields.name',
            $role . '_fields.note',
            $role . '_fields.meta',
            $role . '_fields.default_value',
            DB::raw(Sql::getDbStatement()->groupConcat("{$role}_dictionary.value", "choices", "{$role}_dictionary.id")),
            DB::raw(Sql::getDbStatement()->groupConcat("{$role}_dictionary.id", "dictionary_id", "{$role}_dictionary.id")),
            DB::raw(Sql::getDbStatement()->groupConcat("{$role}_dictionary.order", "dictionary_order", "{$role}_dictionary.id")),
            DB::raw(Sql::getDbStatement()->groupConcat("{$role}_dictionary.meta", "dictionary_meta", "{$role}_dictionary.id")))
            ->leftJoin('field_types', 'field_types.id', '=', $role . '_fields.type')
            ->leftJoin($role . '_dictionary', $role . '_dictionary.field_id', '=', $role . '_fields.id')
            ->groupBy($role . '_fields.id', 'field_types.name');


        return
            new FieldDataTransformer($builder, new FieldToArrayTransformStrategy(['choices', 'default_value', 'dictionary_id', "dictionary_order", "dictionary_meta", ['meta' => 'json'] ]));

    }

    /**
     *
     * @return FieldDataTransformer
     */
    public function modelFieldsExpanded()
    {
        $role = $this->repoContainer->securityContext()->role();

        $dm = $this->repoContainer->securityContext()->getApp()->DM();

        $builder = $this->getModel()

            ->select($role . '_fields.id',
                'field_types.name as type',
                $role . '_fields.symbol_key',
                $role . '_fields.name',
                $role . '_fields.note',
                $role . '_fields.meta',
                $role . '_fields.default_value',
                'data_model_' . $role . '_fields.constraints',
                \DB::raw(Sql::getDbStatement()->groupConcat("{$role}_dictionary.value", "choices", "{$role}_dictionary.id" )),
                \DB::raw(Sql::getDbStatement()->groupConcat("{$role}_dictionary.id", "dictionary_id", "{$role}_dictionary.id")),
                \DB::raw(Sql::getDbStatement()->groupConcat("{$role}_dictionary.order", "dictionary_order", "{$role}_dictionary.id")),
                \DB::raw(Sql::getDbStatement()->groupConcat("{$role}_dictionary.meta", "dictionary_meta", "{$role}_dictionary.id")))
            ->leftJoin('data_model_' . $role . '_fields', $role . '_fields.id', '=', 'data_model_' . $role . '_fields.field_id')
            ->leftJoin('field_types', 'field_types.id', '=', $role . '_fields.type')
            ->leftJoin($role . '_dictionary', $role . '_dictionary.field_id', '=', $role . '_fields.id')
            ->where('data_model_' . $role . '_fields.data_model_id', '=', $dm->id)
            ->groupBy($role . '_fields.id', 'field_types.name', 'data_model_' . $role . '_fields.constraints');

        return
            new FieldDataTransformer($builder, new FieldToArrayTransformStrategy(['choices', 'default_value', 'dictionary_id', 'dictionary_order', 'dictionary_meta', ['meta' => 'json']]));
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function modelFields()
    {
        $role = $this->repoContainer->securityContext()->role(); // or this->model->roleType
        $dm = $this->repoContainer->securityContext()->getApp()->DM();

        return $dm->belongsToMany($this->getModelClassName(), 'data_model_' . $role . '_fields', 'data_model_id', 'field_id')->withPivot('constraints');
    }

    /**
     * @param ProfileInterface $user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function profileFields(ProfileInterface $user)
    {
        $role = $this->repoContainer->securityContext()->role(); // or this->model->roleType
        $userName = $role === BaseDataModel::JOB ? BaseDataModel::JOB : "user";

        return $user->belongsToMany($this->getModelClassName(), $role . '_profile', $userName . '_id', 'field_id')->withPivot('value');
    }

    /**
     * @param array $fields
     *
     * @return Collection
     * @throws \MissionNext\Api\Exceptions\FieldException
     */
    public function addFields(array $fields)
    {
        $symbol_keys = [];
        $withoutChoices = array_map(function ($field) use (&$symbol_keys) {
            $symbol_keys[] = $field["symbol_key"];

            return array_except($field, "choices");
        }, $fields);

        array_walk($withoutChoices, function(&$field, $key){
            if (isset($field['meta']) && is_array($field['meta']) && $meta = $field['meta'] ){
                 $field['meta'] = json_encode($meta);
            }else{
                $field['meta'] = json_encode([]);
            }
        });

        $this->getModel()->insert(
            $withoutChoices
        );

        $addedFields = $this->getModel()->whereIn("symbol_key", $symbol_keys)->get();
        if ($addedFields->count() !== count($withoutChoices)) {

            throw new FieldException("Error inserting fields", FieldException::ON_ADD);
        }
        $addedFields = array_replace_recursive($addedFields->toArray(), $fields);
        $dictionary = [];
        foreach ($addedFields as $addedField) {
            $choices = $addedField["choices"];
            if ($choices) {
                // $choices = explode(",", $choices);
                foreach ($choices as $choice) {
                    $dictionary[] = ["field_id" => $addedField["id"],
                                      "value" => $choice['value'],
                                      "order" => $choice['order'],
                                      "meta" => isset($choice['meta']) && !is_string($choice['meta']) ? json_encode($choice['meta']) : '',
                                    ];
                }
            }
        }
        if (count($dictionary)) {
            $this->getModel()->choices()->insert($dictionary);
        }
        $role = $this->repoContainer->securityContext()->role();

        return $this->fieldsExpanded()->whereIn("{$role}_fields.symbol_key", $symbol_keys)->get();

    }

    /**
     * @param array $fields
     *
     * @return Collection
     */
    public function updateFields(array $fields)
    {
        $ids = array_fetch($fields, "id");
        foreach ($fields as $field) {
            $this->getModel()->where("id", "=", $field["id"])
                ->update(["name" => $field["name"],
                    "default_value" => $field["default_value"],
                    "note" => $field['note'],
                    "meta" => isset($field['meta']) && is_array($field['meta']) ? json_encode($field['meta']) : '',
                ]);

            if ($field["choices"]) {
                $choices = $field["choices"];
                $updateChoices = array_except($choices, 'new');
                $newChoices = isset($choices['new']) ? $choices['new'] : [];
                /** @var  $model Candidate */
                $model = $this->getModel()->find($field["id"]);
                $currentChoicesIds = $model->choices()->get()->lists('id');
                $updateChoiceIds = array_fetch($updateChoices, 'id');


                foreach ($currentChoicesIds as $activeChoiceId) {  //SYNC EXISTENT CHOICES
                    /** @var  $choice \Illuminate\Database\Query\Builder */
                    $choice = $model->choices()->where("id", "=", $activeChoiceId);
                    if (!in_array($activeChoiceId, $updateChoiceIds)) {
                        $choice->delete();
                    } else {
                        $updateChoice = current(array_where($updateChoices, function ($key, $value) use ($activeChoiceId) {

                            return $value["id"] == $activeChoiceId;
                        }));

                        $choice->update(["value" => $updateChoice['value'], "order" => $updateChoice['order'], "meta" => isset($updateChoice['meta']) && is_array($updateChoice['meta']) ? json_encode($updateChoice['meta']) : '' ]);
                    }
                }

                foreach($newChoices as $newChoice){ // CREATE NEW ONE

                    $model->choices()->save(
                        $model->choices()
                        ->create(["value"=>$newChoice['value'], "order" => $newChoice['order'], "meta" => isset($newChoice['meta']) && is_array($newChoice['meta']) ? json_encode($newChoice['meta']) : '' ]));
                }

            }

        }
        $role = $this->repoContainer->securityContext()->role();

        return FieldChoicesFormat::format($this->fieldsExpanded()->whereIn("{$role}_fields.id", $ids)->get());
    }

    /**
     * @param array $ids
     *
     * @return Collection
     *
     * @throws \MissionNext\Api\Exceptions\FieldException
     */
    public function deleteFields(array $ids)
    {
        $symbol_keys = $this->getModel()->whereIn("id", $ids)->lists('symbol_key');

        if (count($symbol_keys) !== count($ids)) {

            throw new FieldException("Specified ids have not related records", FieldException::ON_DELETE);
        }

        $viewFieldRepo = new ViewFieldRepository();
        $viewFieldRepo->deleteByDMSymbolKeys($this->repoContainer->securityContext()->getApp()->DM(), $symbol_keys);
        $this->getModel()->destroy($ids);

        return $this->fieldsExpanded()->get();
    }

} 
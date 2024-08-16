<?php

namespace MissionNext\Repos\ViewField;


use Illuminate\Support\Facades\DB;
use MissionNext\DB\SqlStatement\Sql;
use MissionNext\Models\DataModel\AppDataModel;
use MissionNext\Models\Field\FieldGroup;
use MissionNext\Repos\AbstractRepository;

class ViewFieldRepository extends AbstractRepository implements ViewFieldRepositoryInterface
{

    protected $modelClassName = FieldGroup::class;

    /**
     * @return FieldGroup
     */
    public function getModel()
    {

        return $this->model;
    }

    public function viewFieldsToRemove(AppDataModel $dm, array $symbolKeys ){

        return   $this->model
            ->select("group_fields.symbol_key", "group_fields.group_id")
            ->leftJoin('form_groups', 'form_groups.id', '=', 'group_fields.group_id')
            ->leftJoin('app_forms', 'app_forms.id', '=', 'form_groups.form_id')
            ->leftJoin('app_data_model', 'app_data_model.id', '=', 'app_forms.data_model_id')
            ->where('app_forms.data_model_id', '=', $dm->id)
            ->whereIn('group_fields.symbol_key', $symbolKeys);
    }

    /**
     * @param AppDataModel $dm
     * @param array $symbolKeys
     *
     * @return integer
     */
    public function deleteByDMSymbolKeys(AppDataModel $dm, array $symbolKeys)
    {
        $strSymbolKeys ="'".implode("','", $symbolKeys)."'";

        return DB::statement(Sql::getDbStatement($this)->deleteByDMSymbolKeys($dm, $strSymbolKeys));
    }



} 
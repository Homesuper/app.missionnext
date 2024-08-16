<?php

namespace MissionNext\DB\SqlStatement;


use MissionNext\DB\SqlStatement\RepositoryInterface\IViewFieldRepository;
use MissionNext\Models\DataModel\AppDataModel;

class ViewFieldRepositoryPostgre extends Postgre implements IViewFieldRepository
{
    public function deleteByDMSymbolKeys(AppDataModel $dm, $symbolKeys)
    {

        return "DELETE  FROM group_fields
                        USING form_groups, app_forms, app_data_model
                        WHERE form_groups.id = group_fields.group_id
                        AND app_forms.id = form_groups.form_id
                        AND app_data_model.id = app_forms.data_model_id
                        AND app_data_model.id =  {$dm->id}
                        AND group_fields.symbol_key in ($symbolKeys)
                        ";

    }

} 
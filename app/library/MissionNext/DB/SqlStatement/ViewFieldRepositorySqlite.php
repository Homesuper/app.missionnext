<?php

namespace MissionNext\DB\SqlStatement;


use MissionNext\Models\DataModel\AppDataModel;

class ViewFieldRepositorySqlite extends ViewFieldRepositoryMysql
{
    public function deleteByDMSymbolKeys(AppDataModel $dm, $symbolKeys)
    {

        return   "DELETE FROM group_fields
                        WHERE EXISTS (SELECT 1 FROM group_fields
                        LEFT JOIN form_groups ON form_groups.id = group_fields.group_id
                        LEFT JOIN app_forms ON app_forms.id = form_groups.form_id
                        LEFT JOIN app_data_model ON app_data_model.id = app_forms.data_model_id
                        WHERE app_data_model.id = {$dm->id}
                        AND group_fields.symbol_key IN ($symbolKeys)
                         )";

    }

} 
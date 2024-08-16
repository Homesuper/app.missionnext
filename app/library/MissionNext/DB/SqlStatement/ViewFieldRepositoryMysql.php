<?php
namespace MissionNext\DB\SqlStatement;

use MissionNext\DB\SqlStatement\RepositoryInterface\IViewFieldRepository;
use MissionNext\Models\DataModel\AppDataModel;
class ViewFieldRepositoryMysql extends Mysql implements IViewFieldRepository {

    public function deleteByDMSymbolKeys(AppDataModel $dm, $symbolKeys)
    {

        return "DELETE group_fields
                        FROM group_fields
                        INNER JOIN form_groups ON form_groups.id = group_fields.group_id
                        INNER JOIN app_forms ON app_forms.id = form_groups.form_id
                        INNER JOIN app_data_model ON app_data_model.id = app_forms.data_model_id
                        WHERE app_data_model.id = {$dm->id}
                        AND group_fields.symbol_key IN ($symbolKeys)";

    }

} 
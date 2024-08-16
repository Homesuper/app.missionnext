<?php

namespace MissionNext\DB\SqlStatement\RepositoryInterface;

use MissionNext\Models\DataModel\AppDataModel;

interface IViewFieldRepository
{
    public function deleteByDMSymbolKeys(AppDataModel $dm, $symbolKeys);
} 
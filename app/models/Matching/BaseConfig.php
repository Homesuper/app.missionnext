<?php

namespace MissionNext\Models\Matching;

use MissionNext\Facade\SecurityContext;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\Job\JobField;
use MissionNext\Models\ModelInterface;
use MissionNext\Models\ModelObservable;
use MissionNext\Models\Field\Organization as OrgFieldModel;

class BaseConfig extends ModelObservable implements ModelInterface
{

    protected $table = ''; //must be empty string!!

    protected $role;

    /**
     * @var array
     */
    protected  $matchingFieldModelNames =
        [
            BaseDataModel::JOB => JobField::class ,
            BaseDataModel::ORGANIZATION => OrgFieldModel::class
        ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->role = SecurityContext::getInstance()->role();
        $this->table  = $this->role ? 'matching_'.$this->role.'_config' : '' ;
    }

    /**
     * @return string
     */
    protected function matchingFieldModelName()
    {

        return $this->matchingFieldModelNames[$this->role];
    }

} 
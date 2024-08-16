<?php


namespace MissionNext\Repos\Field;

use MissionNext\Api\Auth\ISecurityContextAware;
use MissionNext\Models\Field\BaseField;
use MissionNext\Repos\AbstractRepository;
use MissionNext\Api\Auth\SecurityContext as SecContext;
use StdClass;

abstract class AbstractFieldRepository extends AbstractRepository implements FieldRepositoryInterface, ISecurityContextAware
{
    /** @var \MissionNext\Api\Auth\SecurityContext */
    protected $securityContext;

    protected $modelClassName = StdClass::class; //заглушка


    public function setSecurityContext(SecContext $securityContext)
    {
        $this->securityContext = $securityContext;

        return $this;
    }

    /**
     * @return BaseField
     */
    protected function bootCurrentFieldModel()
    {

            $this->modelClassName = Field::currentFieldModelName($this->repoContainer->securityContext());
            $this->model = new $this->modelClassName;


        return $this->model;
    }

    /**
     * @return BaseField
     */
    public function getModel()
    {
        return $this->bootCurrentFieldModel();
    }

    /**
     * @return string
     */
    public function getModelClassName()
    {
        $this->bootCurrentFieldModel();

        return $this->modelClassName;
    }
} 
<?php

namespace MissionNext\Repos\Translation;


use MissionNext\Api\Exceptions\SecurityContextException;
use MissionNext\Facade\SecurityContext;
use MissionNext\Filter\RouteSecurityFilter;
use MissionNext\Models\Translation\Field;
use MissionNext\Repos\AbstractRepository;

class FieldRepository extends AbstractRepository implements FieldRepositoryInterface
{
    protected $modelClassName = Field::class;

    private $currentType;

    /**
     * @return Field
     */
    public function getModel()
    {

        return $this->model;
    }

    /**
     * @param $type
     *
     * @return $this
     */
    public function fieldsOfType($type)
    {
        $this->repoContainer->securityContext()->getToken()->setRoles([$type]);
        $this->currentType = $type;

        return new self();
    }
} 
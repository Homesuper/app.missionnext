<?php

namespace MissionNext\Repos\Field;


use MissionNext\Api\Auth\SecurityContext;
use MissionNext\Models\DataModel\AppDataModel;
use MissionNext\Models\Field\BaseField;
use MissionNext\Models\ProfileInterface;
use MissionNext\Repos\RepositoryInterface;
use MissionNext\Models\User\User as UserModel;

interface FieldRepositoryInterface extends  RepositoryInterface {

    /**
     * @return BaseField
     */
    public function getModel();

    public function setSecurityContext(SecurityContext $securityContext);

    public function fieldsExpanded();

    public function modelFieldsExpanded();

    public function modelFields();

    public function profileFields(ProfileInterface $user);

    const KEY = "model_field";

} 
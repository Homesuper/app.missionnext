<?php

namespace MissionNext\Repos\Field;

use MissionNext\Api\Auth\SecurityContext;
use MissionNext\Api\Exceptions\SecurityContextException;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\Field\Agency;
use MissionNext\Models\Field\Candidate;
use MissionNext\Models\Field\Organization;
use MissionNext\Models\Job\Job;
use MissionNext\Models\Job\JobField;

abstract class Field
{

    private static $secContext;

    /**
     * @param SecurityContext $securityContext
     *
     * @return Candidate|Organization|Agency
     *
     * @throws \MissionNext\Api\Exceptions\SecurityContextException
     */
    public static function currentFieldModelName(SecurityContext $securityContext)
    {
        static::$secContext = $securityContext;

        switch ($securityContext->role()) {
            case BaseDataModel::CANDIDATE:
                $modelClassName = Candidate::class;
                break;
            case BaseDataModel::AGENCY:
                $modelClassName = Agency::class;
                break;
            case BaseDataModel::ORGANIZATION:
                $modelClassName = Organization::class;
                break;
            case BaseDataModel::JOB:
                $modelClassName = JobField::class;
                break;
            default:
                throw new SecurityContextException("Undefined role ".$securityContext->role());

        }

        return $modelClassName;

    }


} 
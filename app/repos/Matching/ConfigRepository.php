<?php

namespace MissionNext\Repos\Matching;

use Illuminate\Database\Eloquent\Builder;
use MissionNext\Models\Application\Application as AppModel;
use MissionNext\Models\Field\Candidate as CandidateFieldModel;

/**
 * Class ConfigRepository
 * @package MissionNext\Repos\Matching
 */
class ConfigRepository extends AbstractConfigRepository
{
    /**
     * @param array $configs
     *
     * @return \MissionNext\Models\Matching\Config
     */
    public function insert(array $configs)
    {
        if (count($configs)) {
            $typeRole = $this->sec_context->role();
            $insert = array_map(function ($config) use ($typeRole) {

                return
                    array(
                        "matching_type" => $config["matching_type"],
                        "weight" => $config["weight"],
                        $typeRole . "_field_id" => $config["matching_field_id"],
                        "main_field_id" => $config["main_field_id"],
                        "app_id" => $this->sec_context->getApp()->id,
                    );

            }, $configs);
            $this->getModel()->insert($insert);
        }

        return $this->getModel();
    }
    /**
     * @param $role
     * @param $id
     *
     * @return Builder
     */
    public function configByCandidate($role)
    {
        return $this->getModel()
            ->select($role.'_fields.symbol_key as '.$role.'_key',
                    'candidate_fields.symbol_key as candidate_key',
                     'weight', 'matching_type', 'candidate_fields.type as field_type')
            ->leftJoin($role.'_fields', $role.'_fields.id', '=' , $role.'_field_id' )
            ->leftJoin('candidate_fields', 'candidate_fields.id', '=', 'main_field_id')
            ->where('app_id','=', $this->sec_context->getApp()->id)
            ->distinct();

    }
    /**
     * @param $role
     * @param $id
     *
     * @return Builder
     */
    public function configByJobCandidates()
    {
        return $this->getModel()
            ->select('job_fields.symbol_key as job_key',
                'candidate_fields.symbol_key as candidate_key',
                'weight', 'matching_type', 'candidate_fields.type as field_type')
            ->leftJoin('job_fields', 'job_fields.id', '=' , 'job_field_id' )
            ->leftJoin('candidate_fields', 'candidate_fields.id', '=', 'main_field_id')
            ->where('app_id','=', $this->sec_context->getApp()->id)
            ->distinct();
    }

    /**
     * @param $role
     * @param $id
     *
     * @return Builder
     */
    public function configByCandidateJobs()
    {

        return $this->getModel()
            ->select('job_fields.symbol_key as job_key',
                'candidate_fields.symbol_key as candidate_key',
                'weight', 'matching_type', 'candidate_fields.type as field_type')
            ->leftJoin('job_fields', 'job_fields.id', '=' , 'job_field_id' )
            ->leftJoin('candidate_fields', 'candidate_fields.id', '=', 'main_field_id')
            ->where('app_id','=', $this->sec_context->getApp()->id)
            ->distinct();
    }
    /**
     * @param $role
     * @param $id
     *
     * @return Builder
     */
    public function configByCandidateOrganizations()
    {

        return $this->getModel()
            ->select('organization_fields.symbol_key as organization_key',
                'candidate_fields.symbol_key as candidate_key',
                'weight', 'matching_type', 'organization_fields.type as field_type')
            ->leftJoin('organization_fields', 'organization_fields.id', '=' , 'organization_field_id' )
            ->leftJoin('candidate_fields', 'candidate_fields.id', '=', 'main_field_id')
            ->where('app_id','=', $this->sec_context->getApp()->id)
            ->distinct();
    }

    /**
     * @param $role
     * @param $id
     *
     * @return Builder
     */
    public function configByOrganizationCandidates()
    {

        return $this->getModel()
            ->select('organization_fields.symbol_key as organization_key',
                'candidate_fields.symbol_key as candidate_key',
                'weight', 'matching_type', 'organization_fields.type as field_type')
            ->leftJoin('organization_fields', 'organization_fields.id', '=' , 'organization_field_id' )
            ->leftJoin('candidate_fields', 'candidate_fields.id', '=', 'main_field_id')
            ->where('app_id','=', $this->sec_context->getApp()->id)
            ->distinct();
    }

} 
<?php

namespace MissionNext\Repos\FormGroup;

use Illuminate\Database\Query\Builder;
use MissionNext\Api\Auth\ISecurityContextAware;
use MissionNext\Api\Auth\SecurityContext;
use MissionNext\DB\SqlStatement\Sql;
use MissionNext\Models\Form\FormGroup;
use MissionNext\Repos\AbstractRepository;
use MissionNext\Repos\Field\FieldToArrayTransformStrategy;
use MissionNext\Repos\Field\FieldDataTransformer;

class FormGroupRepository extends AbstractRepository implements FormGroupRepositoryInterface, ISecurityContextAware
{
    protected $modelClassName = FormGroup::class;
    /** @var  SecurityContext */
    protected $securityContext;

    public function setSecurityContext(SecurityContext $securityContext)
    {

        $this->securityContext = $securityContext;
    }

    /**
     * @return FormGroup
     */
    public function getModel()
    {

        return $this->model;
    }

    /**
     * @return Builder
     */
    public function dependentFields()
    {
       $dm = $this->securityContext->getApp()->DM();

       $builder =   $this->getModel()
            ->select('form_groups.depends_on', 'form_groups.depends_on_option', \DB::raw(Sql::getDbStatement()->groupConcat('group_fields.symbol_key','symbol_keys')) )
            ->leftJoin('app_forms', 'app_forms.id', '=', 'form_groups.form_id')
            ->leftJoin('app_data_model', 'app_data_model.id', '=', 'app_forms.data_model_id')
            ->leftJoin('group_fields', 'group_fields.group_id', '=', 'form_groups.id')
            ->where('app_forms.data_model_id', '=', $dm->id)
            ->where('form_groups.depends_on', '<>', '')
            ->groupBy('form_groups.depends_on', 'form_groups.depends_on_option');

        return
            new FieldDataTransformer($builder, new FieldToArrayTransformStrategy(['choices','symbol_keys']));
    }

} 
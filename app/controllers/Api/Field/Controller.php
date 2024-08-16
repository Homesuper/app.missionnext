<?php
namespace MissionNext\Controllers\Api\Field;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use MissionNext\Api\Exceptions\ProfileException;
use MissionNext\Api\Response\RestResponse;
use Illuminate\Support\Facades\Input;
use MissionNext\Api\Service\ResponseDataFormat\FieldChoicesFormat;
use MissionNext\Controllers\Api\BaseController;
use MissionNext\Models\Field\BaseField;
use MissionNext\Models\Field\Candidate;
use MissionNext\Repos\Field\FieldRepositoryInterface;

/**
 * Class Field Controller
 *
 * @package MissionNext\Controllers\Api\Field
 */
class Controller extends BaseController
{

    /**
     * @param string $type
     *
     * @return RestResponse
     */
    public function getIndex($type)
    {

        return new RestResponse(FieldChoicesFormat::format($this->fieldRepo()->fieldsExpanded()->get()));
    }

    /**
     * @param string $type
     *
     * @return RestResponse
     */
    public function postIndex($type)
    {
        /** @var  $request \Symfony\Component\HttpFoundation\Request */
        $request = Request::instance()->request;
        $fields = $request->has("fields") ? $request->get("fields") : [];


        return new RestResponse($this->fieldRepo()->addFields($fields));
    }

    /**
     * @param string $type
     *
     * @return RestResponse
     */
    public function putIndex($type)
    {
        /** @var  $request \Symfony\Component\HttpFoundation\Request */
        $request = Request::instance()->request;

        $fields = $request->has("fields") ? $request->get("fields") : [];


        return new RestResponse($this->fieldRepo()->updateFields($fields));
    }

    /**
     * @param string $type
     *
     * @return RestResponse
     */
    public function deleteIndex($type)
    {
        /** @var  $request \Symfony\Component\HttpFoundation\Request */
        $query = Request::instance()->query;

        $ids = $query->has("ids") ? $query->get("ids") : [];

        return new RestResponse($this->fieldRepo()->deleteFields($ids));
    }


    /**
     * @param string $type
     *
     * @return RestResponse
     */
    public function getModel($type)
    {

        return new RestResponse(FieldChoicesFormat::format($this->fieldRepo()->modelFieldsExpanded()->get()));

    }

    /**
     * @param integer $fieldId
     *
     * @return RestResponse
     */
    public function postChoices($type, $fieldId)
    {
        $choices = $this->request->request->get('choices');

        $choicesIds = array_fetch($choices, 'id');
        /** @var  $field Candidate */
        $field = $this->fieldRepo()->getModel()->findOrFail($fieldId);
        /** @var  $choiceModels Collection */
        $choiceModels = $field->choices()->whereIn('id',$choicesIds)->get();
        $choiceModels->each(function($model) use ($choices){
            $modelData = array_first($choices, function($key, $value) use($model)
                         {

                            return $value['id'] == $model->id;
                         });

            if (isset($modelData['meta'])){

                $modelData['meta'] = json_encode($modelData['meta']);
            }
            $modelData = array_except($modelData, 'id');

            $model->update($modelData);
        });


        return new RestResponse(true);
    }


    /**
     * @param string $type
     *
     * @return RestResponse
     */
    public function postModel($type)
    {
        $fields = Input::get("fields", []);
        $sync = [];
        foreach ($fields as $field) {
            $sync[$field["id"]] = ["constraints" => $field["constraints"]];
        }

        $mFields = $this->fieldRepo()->modelFields();

        $idsBeforSync = $mFields->getRelatedIds();

        count($fields)
            ? $mFields->sync($sync)
            : $mFields->detach();

        $idsAfterSync = $mFields->getRelatedIds();

        $viewIdsToRemove = array_diff($idsBeforSync, $idsAfterSync);

        if (!empty($viewIdsToRemove)) {
            $symbol_keys = $this->fieldRepo()->getModel()->whereIn('id', $viewIdsToRemove)->lists('symbol_key');
            $this->viewFieldRepo()->deleteByDMSymbolKeys($this->getApp()->DM(), $symbol_keys);
        }

        return new RestResponse($mFields->get());
    }

}
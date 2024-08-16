<?php

namespace MissionNext\Filter;

use Illuminate\Routing\Route as Router;
use Illuminate\Http\Request as LRequest;
use Illuminate\Support\Facades\Route;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\Job\Job;
use MissionNext\Models\User\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoleChecker
{
    const CHECK = "check";
    /** @var  Router */
    private $router;


    private $roleInputs;

    private $roleAliases = [
                          'org' => BaseDataModel::ORGANIZATION,
                          'job' => BaseDataModel::JOB,
                          'can' => BaseDataModel::CANDIDATE,
                          'ag'  => BaseDataModel::AGENCY
                           ];

    public function check(Router $route, LRequest $request)
    {
        $userModel = new User();
        $jobModel = new Job();
        $this->router = $route;

        $this->roleInputs =  [ "candidate" => ["model" =>$userModel, "role" => BaseDataModel::CANDIDATE],
            "job" => ["model" => $jobModel, "role" => BaseDataModel::JOB],
            "agency" =>["model" => $userModel, "role" => BaseDataModel::AGENCY],
            "organization"  => ["model" => $userModel, "role" => BaseDataModel::ORGANIZATION]
        ];

        $this->checkCombinations($route->parametersWithoutNulls());
    }


    private function checkCombinations(array $params)
    {
        foreach($params as $param => $order){

            if (str_contains($param, "_or_")){
                $roles =  explode("_or_", $param);
                if ($userId = $this->router->parameter($param)){
                    /** @var  $model User */
                    $model = User::find($userId) ? : Job::find($userId);
                    if (!$model){

                        throw new NotFoundHttpException();
                    }
                    foreach($roles as $role){
                        if ($model->hasRole($this->roleAliases[$role])){

                            $this->router->setParameter($param, $model);
                            break 2;
                        }
                    }
                }
                throw new NotFoundHttpException();
            }

            if (in_array($param, RouteSecurityFilter::$ALLOWED_ROLES)){
                if ($userId = $this->router->parameter($param)){
                    $model = $this->roleInputs[$param]['model']->find($userId);
                    if (!$model || !$model->hasRole($param)){

                        throw new NotFoundHttpException();
                    }
                    $this->router->setParameter($param, $model);
                }
            }

        }
    }

} 
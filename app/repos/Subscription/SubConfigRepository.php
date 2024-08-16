<?php


namespace MissionNext\Repos\Subscription;

use Boris\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use MissionNext\Models\Application\Application;
use MissionNext\Models\Configs\AppConfigs;
use MissionNext\Models\Configs\GlobalConfig;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\Subscription\SubConfig;
use MissionNext\Repos\AbstractRepository;

class SubConfigRepository extends AbstractRepository implements SubConfigRepositoryInterface
{
    protected $modelClassName = SubConfig::class;
    /**
     * @return SubConfig
     */
    public function getModel()
    {

        return $this->model;
    }

    /**
     * @param $appId
     *
     * @return array
     */
    public function config($appId)
    {

       return $this->structure($this->getModel()->whereAppId($appId)->get());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function allConfigs()
    {

        return  Application::with(['configs' => function($query){
                 $query->where('key','agency_trigger')
                        ->orWhere('key','block_website');
                }, 'subConfigs'])
            ->get();
    }

    /**
     * @param Collection $configs
     *
     * @return array
     */
    private function structure(Collection $configs)
    {

        if (!$configs->count()){

            return  SubConfig::defConfig();
        }

        $return = [];
        foreach($configs as  $config){
            $return[$config->role]['role'] = [ 'key' =>$config->role, 'label' => BaseDataModel::label($config->role) ];
            $return[$config->role]['partnership'][] =
                ["price_month" => intval($config->price_month), "level" =>$config->partnership,  "price_year" =>  intval($config->price_year),
                  "partnership_status" => (boolean)$config->partnership_status,
                 ];

        }
        $conf = [];
        $conf[] = $return[BaseDataModel::ORGANIZATION];
        $partnership = $conf[0]['partnership'];
        $conf[0]['partnership'][0] = current(array_filter($partnership, function($p){
            return $p['level'] === 'limited';
        }));

        $conf[0]['partnership'][1] = current(array_filter($partnership, function($p){
            return $p['level'] === 'basic';
        }));
        $conf[0]['partnership'][2] = current(array_filter($partnership, function($p){
            return $p['level'] === 'plus';
        }));
        $conf[] = $return[BaseDataModel::AGENCY];
        $conf[] = $return[BaseDataModel::CANDIDATE];

        return $conf;
    }

} 
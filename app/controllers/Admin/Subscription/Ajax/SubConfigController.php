<?php

namespace MissionNext\Controllers\Admin\Subscription\Ajax;


use Illuminate\Support\Facades\Response;
use MissionNext\Controllers\Admin\AdminBaseController;
use MissionNext\Models\Configs\AppConfigs;
use MissionNext\Models\Configs\GlobalConfig;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\Subscription\Partnership;
use MissionNext\Models\Subscription\SubConfig;
use MissionNext\Repos\Subscription\SubConfigRepository;
use MissionNext\Repos\Subscription\SubConfigRepositoryInterface;

class SubConfigController extends AdminBaseController
{
    const ROUTE_PREFIX = 'ajax.sub.config';


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndex()
    {
        $appId = $this->request->query->get('app');
        /** @var  $repo SubConfigRepository */
        $repo = $this->repoContainer[SubConfigRepositoryInterface::KEY];
        $globalConfig = new GlobalConfig();
        $conFee = $globalConfig->conFee();
        $discount = $globalConfig->subscriptionDiscount();
        $gracePeriod = $globalConfig->gracePeriod();

        return Response::json([ "config" => $repo->config($appId), GlobalConfig::CON_FEE => $conFee,
            GlobalConfig::SUBSCRIPTION_DISCOUNT => $discount,
            GlobalConfig::GRACE_PERIOD => $gracePeriod
                            ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function postIndex()
    {
        $configs = $this->request->request->get('configs');
        $appId = $this->request->request->get('app');
        $conFee = intval($this->request->request->get(GlobalConfig::CON_FEE));
        $discount = intval($this->request->request->get(GlobalConfig::SUBSCRIPTION_DISCOUNT));
        $gracePeriod = intval($this->request->request->get(GlobalConfig::GRACE_PERIOD));

        GlobalConfig::updateOrCreate( ['key' => GlobalConfig::CON_FEE], ['value' => $conFee] );
        GlobalConfig::updateOrCreate( ['key' => GlobalConfig::SUBSCRIPTION_DISCOUNT], ['value' => $discount] );
        GlobalConfig::updateOrCreate( ['key' => GlobalConfig::GRACE_PERIOD], ['value' => $gracePeriod] );

        foreach($configs as $config){
           foreach($config['partnership'] as $p ) {
               SubConfig::updateOrCreate([
                   'app_id' => $appId,
                   'partnership' => $p['level'],
                   'role' => $config['role']['key']
               ],[
                   "price_month" => $p['price_month'],
                   "price_year" => $p['price_year'],
                   "partnership_status" => $p["partnership_status"],
               ]);
           }
        }


        return Response::json(SubConfig::whereAppId( $appId )->get()->toArray());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function putPrice()
    {
        $data = $this->request->request->all();
        SubConfig::updateOrCreate($data['where'], $data['update']);

        return Response::json(SubConfig::whereAppId( $data['where']['app_id'] )->get()->toArray());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function putGlobal()
    {
        $data = $this->request->request->all();
        GlobalConfig::updateOrCreate( $data['where'], $data['update']);

        return Response::json(GlobalConfig::where($data['where'])->first());
    }

} 
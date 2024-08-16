<?php


namespace MissionNext\Api\Service\Matching\Queue;

use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use MissionNext\Api\Service\Matching\Matching as ServiceMatching;
use MissionNext\Models\Matching\Results;
use MissionNext\Repos\CachedData\UserCachedRepository;


class InsertQueue
{

    public function fire($job, $data)
    {
        $mainData = $data["mainData"];
        $matchingData = $data["matchingData"];
        $forUserType = $data["forUserType"];
        $userType = $data["userType"];
        $userId = $data["userId"];
        $matchingClass = $data["matchingClass"];
        $config = $data["config"];
        $app_id = $data["app_id"];

        /** @var  $Matching ServiceMatching */
        $Matching = new $matchingClass($mainData, $matchingData, $config);

        $matchingData = $Matching->matchResults();

        $dateTime = (new \DateTime())->format("Y-m-d H:i:s");

        if (!empty($matchingData)) {

            $insertData = [];
            $deleteIds = [];
            $insertOppositeData = [];
            $k = 0;
            foreach($matchingData as $match){

                $insertData[$k]['user_type'] = $userType;
                $insertData[$k]['user_id'] = $match['id'];
                $insertData[$k]['for_user_id'] = $userId;
                $insertData[$k]['for_user_type'] = $forUserType;
                $insertData[$k]['matching_percentage'] = $match['matching_percentage'];
                $insertData[$k]['app_id'] = $app_id;
                $insertData[$k]['data'] = json_encode($match);
                $insertData[$k]['created_at'] = $dateTime;
                $insertData[$k]['updated_at'] = $dateTime;

                $deleteIds[] = $match['id'];

                $mainData['results'] = $match['opposite_results'];
                $mainData['matching_percentage'] = $match['matching_percentage'];

                $insertOppositeData[$k]['user_type'] = $forUserType;
                $insertOppositeData[$k]['user_id'] = $userId;
                $insertOppositeData[$k]['for_user_id'] = $match['id'];
                $insertOppositeData[$k]['for_user_type'] = $userType;
                $insertOppositeData[$k]['matching_percentage'] = $match['matching_percentage'];
                $insertOppositeData[$k]['app_id'] = $app_id;
                $insertOppositeData[$k]['data'] = json_encode($mainData);
                $insertOppositeData[$k]['created_at'] = $dateTime;
                $insertOppositeData[$k]['updated_at'] = $dateTime;

                $k++;
            }

            Results::where('app_id', $app_id)->where('for_user_id', $userId)->whereIn('user_id', $deleteIds)->delete();
            Results::where('app_id', $app_id)->where('user_id', $userId)->whereIn('for_user_id', $deleteIds)->delete();

            Results::insert($insertData);
            Results::insert($insertOppositeData);
        }

        $job->delete();
    }

}


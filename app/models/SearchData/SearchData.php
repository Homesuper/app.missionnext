<?php

namespace MissionNext\Models\SearchData;


use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use MissionNext\Facade\SecurityContext;
use MissionNext\Models\FolderNotes\FolderNotes;
use MissionNext\Models\ModelInterface;

class SearchData extends Model implements ModelInterface
{
   protected $table = "search_data";

   protected $fillable = [ "user_type", "search_name", "search_type", "user_id", "data", "app_id" ];

    /**
     * @param $query
     * @param $searchType
     * @param $userType
     * @param $userId
     *
     * @return QueryBuilder
     */
    public function scopeFindData($query, $searchType, $userType, $userId)
    {

        /** @var $query Builder */
        return
            $query
//              ->select('search_data.data', $folderNotesTable.".notes", $folderNotesTable.".folder")
//              ->leftJoin($folderNotesTable, function($leftJoin) use ($folderNotesTable, $searchType){
//                  $leftJoin->on($folderNotesTable.".for_user_id", "=", "search_data.user_id" )
//                      ->where($folderNotesTable.".user_type", '=', $searchType );
//
//              } )
            ->where("search_data.search_type","=",$searchType)
            ->where("search_data.user_type", "=", $userType)
            ->where("app_id", "=", SecurityContext::getInstance()->getApp()->id())
            ->where("search_data.user_id", "=", $userId);
    }
} 
<?php


namespace MissionNext\Api\Service\DataTransformers;


use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use MissionNext\Repos\TransformDataStrategy;
use Illuminate\Support\Collection;

class UserCachedDataStrategy extends TransformDataStrategy
{
    protected  $jsonDataKeys;

    public function __construct(array $jsonDataKeys = [])
    {
        $this->jsonDataKeys = $jsonDataKeys;
    }

    /**
     * @param \IteratorAggregate $dataCollection
     *
     * @return Collection
     */
    public function transform(\IteratorAggregate $dataCollection)
    {
      $keys = [];
      $totalPages = $dataCollection instanceof Paginator ? $dataCollection->getLastPage() : null;

      foreach($this->jsonDataKeys as $data){
          if (is_array($data)){
              $keys[] = key($data);
          }else{
              $keys[] = $data;
          }
      }

      return $dataCollection->each(function($d) use ($keys, $totalPages){
           if (!is_null($totalPages)){
               $d->totalPages = $totalPages;
           }
           foreach($this->jsonDataKeys as $jsonKey){
               if (is_array($jsonKey)){
                   if (current($jsonKey)){
                       $key = key($jsonKey);
                       $data = json_decode($d->$key, true);
                       $props = array_keys($data);

                       foreach($props as $prop){
                          if (!in_array($prop, $keys)){
                              $d->$prop = $data[$prop];
                          }
                       }
                       unset($d->$key);
                       continue;
                   }else{
                       $jsonKey = key($jsonKey);
                       $d->$jsonKey = json_decode($d->$jsonKey, true);
                   }

               }else{
                  // dd($d->$jsonKey);
                   $d->$jsonKey = json_decode($d->$jsonKey, true);
               }


           }
           if (empty($this->jsonDataKeys)){
               // default key data
               $d->data = json_decode($d->data, true);
               $props = array_keys($d->data);
               foreach($props as $prop){
                   $d->$prop = $d->data[$prop];
               }
               unset($d->data);
           }

       });
    }
} 
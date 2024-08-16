<?php


namespace MissionNext\Api\Service\Matching\Queue;


class TestQueue {


     public function fire($job, $data)
     {
         file_put_contents('/srv/www/laravel4test1/public/some.txt', $data[0], FILE_APPEND);
         $job->delete();
     }
} 
<?php


namespace MissionNext\Repos\CachedData;


interface UserCachedRepositoryInterface
{
    const KEY = "user_cached_data";

    public function dataOfType($type);
} 
<?php


namespace MissionNext\Models;


use Illuminate\Support\Collection;
use MissionNext\Models\Application\Application;
use MissionNext\Repos\User\JobRepository;
use MissionNext\Repos\User\UserRepository;

interface ProfileInterface extends ModelInterface
{
    public function appIds();

    /**
     * @return Collection
     */
    public function appData();

    public function hasApp(Application $app);

    public function addApp(Application $app);

    public function hasRole($check);

    public function role();

    /**
     * @return UserRepository|JobRepository
     */
    public function getRepo();
} 
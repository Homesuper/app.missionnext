<?php

namespace MissionNext\Models\CacheData;

use MissionNext\Facade\SecurityContext;
use MissionNext\Models\AbstractDynamicModel;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\ModelInterface;
use MissionNext\Models\Profile;
use MissionNext\Models\ProfileInterface;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractCachedData extends AbstractDynamicModel
{
    /**
     * @param ProfileInterface $user
     *
     *  @return $this
     */
    public function setUser(ProfileInterface $user)
    {
        $this->id = $user->id;

        return $this;
    }

    /**
     * @param Profile $profile
     *
     * @return $this
     */
    public function setProfileData(Profile $profile)
    {
        $this->data = $profile->toJson();

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {

        return json_decode($this->data, true);
    }
} 
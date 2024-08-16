<?php
namespace MissionNext\Models\DataModel;

use Illuminate\Database\Eloquent\Model as Eloquent;

class BaseDataModel extends Eloquent
{
    const CANDIDATE = "candidate";
    const ORGANIZATION = "organization";
    const AGENCY = "agency";

    const JOB = "job";

    /**
     * @return array
     */
    public static function userRoles()
    {

        return [static::CANDIDATE, static::AGENCY, static::ORGANIZATION];
    }

    /**
     * @return array
     */
    public static function allRoles()
    {

        return array_merge(static::userRoles(), [static::JOB]);
    }

    /**
     * @param $role
     *
     * @return string
     */
    public static function label($role){
        $labels = [
                   static::CANDIDATE => "Candidate",
                   static::ORGANIZATION => "Receiving Organization",
                   static::AGENCY => "Service Organization"
                  ];

        return $labels[$role];
    }


    public static function createCandidate()
    {

        return static::create(["type" => self::CANDIDATE]);
    }

    public static function createOrganization()
    {

        return static::create(["type" => self::ORGANIZATION]);
    }

    public static function createAgency()
    {

        return static::create(["type" => self::AGENCY]);
    }

    public static function createJob()
    {

        return static::create(["type" => self::JOB]);
    }

} 
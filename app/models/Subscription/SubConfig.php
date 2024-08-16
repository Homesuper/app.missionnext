<?php

namespace MissionNext\Models\Subscription;


use Illuminate\Database\Eloquent\Model;
use MissionNext\Models\Application\Application;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\ModelInterface;

class SubConfig extends Model implements ModelInterface
{
    public $timestamps = false;

    protected $table = "subscription_configs";

    protected $fillable = ['app_id', 'role', 'partnership', 'price_year', 'price_month', 'partnership_status'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function app()
    {

        return $this->belongsTo(Application::class, 'app_id');
    }

    /**
     * @return array
     */
    public static function defConfig(){

        return [

            [
                "role" => [ "key" => BaseDataModel::ORGANIZATION, "label" => BaseDataModel::label(BaseDataModel::ORGANIZATION)],
                "partnership" =>
                    [
                        [ "price_month" => 0, "level" => Partnership::LIMITED,  "price_year" => 0, "partnership_status" => true   ],
                        [ "price_month" => 0, "level" =>  Partnership::BASIC,  "price_year" => 0, "partnership_status" => true   ],
                        [ "price_month" => 0, "level" => Partnership::PLUS,  "price_year" => 0 , "partnership_status" => true  ],
                    ]

            ],

            [
                "role" => [ "key" => BaseDataModel::AGENCY, "label" => BaseDataModel::label(BaseDataModel::AGENCY)],
                "partnership" =>
                    [
                        [ "price_month" => 0, "level" =>'',  "price_year" => 0, "partnership_status" => false   ],

                    ]

            ],
            [
                "role" => [ "key" => BaseDataModel::CANDIDATE, "label" => BaseDataModel::label(BaseDataModel::CANDIDATE)],
                "partnership" =>
                    [
                        [ "price_month" => 0, "level" =>'',  "price_year" => 0, "partnership_status" => false  ],

                    ],

            ],
        ];
    }
} 
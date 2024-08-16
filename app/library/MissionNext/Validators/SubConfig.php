<?php


namespace MissionNext\Validators;



class SubConfig extends Validator
{

    public static $rules =  [
        "app_id" => "required|exists:application,id",
        "role" => "required|in:candidate,agency,organization",
        "partnership" => "required|in:limited,basic,plus",
        "cost" => "required|numeric",
        "period" => "required|integer|between:0,9999",
        "is_recurrent" => ""
    ];
} 
<?php

namespace MissionNext\Validators;


class Job extends Validator
{
    public static $rules = [ //must declare all fields
        "name" => "required|between:3,100",
        "symbol_key" => "required|unique:jobs,symbol_key",
        "organization_id" => "required|integer|exists:users,id"
    ];

} 
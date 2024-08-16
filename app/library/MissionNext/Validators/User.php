<?php
namespace MissionNext\Validators;

use Illuminate\Support\Facades\Validator as FValidator;

class User extends Validator
{
    public static $rules =  [
        "password" => "required|between:3,100",
        "email" => "required|unique:users,email|email",
        "role" => "exists:roles,role",
        "username" => "required|unique:users,username"
    ];

    public function passes()
    {
        if ($this->model) {
            if (isset($this->input["email"])) {
                static::$rules["email"] = ($this->model->email === $this->input["email"]) ? "required|email" : static::$rules["email"];
            }

            if (isset($this->input["username"])) {
                static::$rules["username"] = ($this->model->username === $this->input["username"]) ? "required" : static::$rules["username"];
            }
        }

        /** @var  $validation \Illuminate\Validation\Validator */
        $validation = FValidator::make($this->input, static::$rules);

        if ($validation->passes()) return true;

        $this->errors = $validation->messages();

        return false;
    }
} 
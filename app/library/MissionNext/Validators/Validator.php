<?php

namespace MissionNext\Validators;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FValidator;
use Illuminate\Support\MessageBag;

abstract class Validator
{

    protected $input;

    protected $errors;

    protected $model;

    protected $validator;

    public static $rules;

    /** @var  Request */
    protected  $request;

    public function __construct(Request $request, Model $model = null)
    {
        $this->validator = FValidator::make([], []);
        $this->request = $request;
        $this->model = $model;
        $this->input = $this->getInput();
    }

    /**
     * @return \Illuminate\Validation\Validator
     */
    public function validator()
    {

        return $this->validator;
    }

    public function passes()
    {
        /** @var  $validation \Illuminate\Validation\Validator */
        $validation = FValidator::make($this->input, static::$rules);
        $this->validator = $validation;

        if ($validation->passes()){
            if ($this->model){
                foreach($this->getInput() as $property => $value ){
                    $this->model->$property = $value;
                }
            }

            return true;
        }

        $this->errors = $validation->messages();

        return false;
    }

    /**
     * @return MessageBag
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    protected  function getInput()
    {
        $request = $this->request;
        $fields = array_keys(static::$rules);
        $input = [];
        foreach($fields as $field){
            if (!$request->request->has($field)){
                continue;
            }
            $input[$field] = $request->request->get($field);
        }
        static::$rules =  array_intersect_key( static::$rules, $input );

        return $input;
    }

    /**
     * @param $id
     * @param $field
     *
     * @return $this
     */
    public function updateRuleUnique($id, $field)
    {
        static::$rules[$field] = static::$rules[$field].",".$id;

        return $this;
    }
} 
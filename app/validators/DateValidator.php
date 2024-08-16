<?php
namespace MissionNext\Custom\Validators;

use Illuminate\Validation\Validator;

class DateValidator extends Validator
{
    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateYmdMoreThan($attribute, $value, $parameters)
    {
        $params = $this->params($parameters);

        return new \DateTime($value) > (new \DateTime)->modify("+ {$params->years} years {$params->months} months {$params->days} days");
    }

    /**
     * @param $message
     * @param $attribute
     * @param $rule
     * @param $parameters
     * @return mixed
     */
    protected function replaceYmdMoreThan($message, $attribute, $rule, $parameters)
    {
        $params = $this->params($parameters);

        $yearStr = $monthStr = $dayStr = null;
        if ($params->years > 0) {
            $yearStr = ($params->years > 1) ? $params->years." years " : $params->years." year ";
        }

        if ($params->months > 0) {
            $monthStr = ($params->months > 1) ? $params->months." months " : $params->months." month ";
        }

        if ($params->days > 0) {
            $dayStr = ($params->days > 1) ? $params->days." days " : $params->days." day ";
        }

        return str_replace([':required_years', ':required_months', ':required_days'], [$yearStr, $monthStr, $dayStr], $message);
    }

    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateYmdLessThan($attribute, $value, $parameters)
    {
        $params = $this->params($parameters);

        return new \DateTime($value) < (new \DateTime)->modify("+ {$params->years} years {$params->months} months {$params->days} days");
    }

    /**
     * @param $message
     * @param $attribute
     * @param $rule
     * @param $parameters
     * @return mixed
     */
    protected function replaceYmdLessThan($message, $attribute, $rule, $parameters)
    {
        $params = $this->params($parameters);

        $yearStr = $monthStr = $dayStr = null;
        if ($params->years > 0) {
            $yearStr = ($params->years > 1) ? $params->years." years " : $params->years." year ";
        }

        if ($params->months > 0) {
            $monthStr = ($params->months > 1) ? $params->months." months " : $params->months." month ";
        }

        if ($params->days > 0) {
            $dayStr = ($params->days > 1) ? $params->days." days " : $params->days." day ";
        }

        return str_replace([':required_years', ':required_months', ':required_days'], [$yearStr, $monthStr, $dayStr], $message);
    }

    /**
     * @param array $parameters
     *
     * @return \ArrayObject
     */
    private function params(array $parameters)
    {
        $years = intval(trim($parameters[0]));
        $months = isset($parameters[1]) ? intval(trim($parameters[1])) : 0;
        $days = isset($parameters[2]) ? intval(trim($parameters[2])) : 0;

        return new \ArrayObject(["years" => $years, "months" => $months, "days" => $days], 2);
    }

} 
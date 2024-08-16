<?php


namespace MissionNext\Models\Authorize;



class Renewal
{
    /** @var \ArrayObject  */
    private $container;
    private $params =
        [
            'x_subscription_id', 'api', 'x_amount',  'x_description', 'x_response_code',  'x_subscription_paynum', 'x_trans_id'
        ];

    public function __construct(array $input)
    {
        $this->container = new \ArrayObject($input, 2);
        foreach($this->params as $param){
            $this->setParam($param);
        }
    }

    const APPROVED = 1,
          DECLINED = 2,
          ERROR    = 3;

    public $x_subscription_id,
           $api,
           $x_trans_id,
           $x_amount,
           $x_description,
           $x_response_code,
           $x_subscription_paynum;


    /**
     * @return bool
     */
    public function isApproved()
    {

        return $this->container->offsetGet('x_response_code') == static::APPROVED;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setParam($name)
    {
        $this->$name = $this->container->offsetExists($name) ? $this->container->$name : null;

        return $this;
    }


} 
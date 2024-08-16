<?php

namespace MissionNext\Api\Service\Payment;


use MissionNext\Api\Auth\SecurityContext;
use MissionNext\Api\Service\Payment;

abstract class AbstractPaymentGateway implements PaymentGatewayInterface
{
    /**
     * @var \AuthorizeNetAIM
     */
    protected $paymentGateWay;

    /** @var  \AuthorizeNetARB */
    protected $recurringBilling;

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @param SecurityContext $securityContext
     *
     * @return $this
     */
    public function setSecurityContext(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;

        return $this;
    }

    abstract public function getService();

} 
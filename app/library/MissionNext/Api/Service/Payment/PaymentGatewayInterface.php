<?php

namespace MissionNext\Api\Service\Payment;


interface PaymentGatewayInterface
{
    public function getService();

    public function getRecurringBilling();
}
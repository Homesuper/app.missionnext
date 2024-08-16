<?php


namespace MissionNext\Controllers\Api\Subscription;


use MissionNext\Api\Response\RestResponse;
use MissionNext\Controllers\Api\BaseController;
use MissionNext\Models\Coupon\Coupon;

/**
 * Class CouponController
 * @package MissionNext\Controllers\Api\Subscription
 */
class CouponController extends BaseController
{
    /**
     * @return RestResponse
     */
    public function postCode()
    {

        return new RestResponse(Coupon::whereCode($this->request->request->get('code'))->first());
    }
} 
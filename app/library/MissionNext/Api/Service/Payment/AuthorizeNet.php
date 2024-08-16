<?php

namespace MissionNext\Api\Service\Payment;


use Carbon\Carbon;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Config;
use MissionNext\Api\Auth\ISecurityContextAware;
use MissionNext\Api\Exceptions\AuthorizeException;
use MissionNext\Api\Exceptions\BadDataException;
use MissionNext\Models\Configs\GlobalConfig;
use MissionNext\Models\Application\Application as AppModel;
use MissionNext\Models\Coupon\Coupon;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\Subscription\Partnership;
use MissionNext\Models\Subscription\Subscription;
use MissionNext\Models\User\User;
use MissionNext\Repos\Subscription\SubscriptionRepository;
use MissionNext\Repos\Subscription\SubscriptionRepositoryInterface;

class AuthorizeNet extends AbstractPaymentGateway implements ISecurityContextAware
{
    private $discount;
    private $fee;
    private $apps;
    private $defaults;

    private $part_multiplier;

    private $renew_type;
    private $first_payment;

    private $app;

    public  function __construct(\AuthorizeNetAIM $authorizeNet, \AuthorizeNetARB $authorizeNetARB, Application $app)
    {
        $this->recurringBilling = $authorizeNetARB;
        $this->recurringBilling->setSandbox(Config::get('app.set_sandbox'));

        $this->paymentGateWay = $authorizeNet;
        $this->paymentGateWay->setSandbox(Config::get('app.set_sandbox'));

        $this->app = $app;
        $this->discount = (new GlobalConfig)->subscriptionDiscount();
        $this->fee = (new GlobalConfig())->conFee();
        $this->apps = $this->prepareApps(AppModel::with('configs')->with('subConfigs')->get()->toArray());
    }

    public function processRequest($data, $subsRepo = null){

        $this->defaults = $this->getDefaults($data['user_id'], $subsRepo);

        $user = User::find($data['user_id']);

        $price = $this->calcPrice($data['subscriptions'], $user->role(), $data['period'], $data['type'], $data['coupon'], $data['renew_type']);

        $this->renew_type = $data['renew_type'];

        if($price > 0){
            if($data['renew_type'] == 'm'){

                $this->cancelAuthorizeSubs();

                return $this->processARB($user, $data, $price);
            } else {
                return $this->processAIM($user, $data, $price);
            }

        } else {

            if($data['coupon']){
                Coupon::disable($data['coupon']['code']);
            }

            $transaction_id = 0;

            $response = $this->prepareSubscriptionResponse($transaction_id, $price, $user['id'], $user->role(), $data['period'], $data['recurring'], $data['subscriptions']);

            return $response;
        }

    }

    /**
     * @return \AuthorizeNetAIM
     */
    public function getService()
    {
        return $this->paymentGateWay;
    }

    private function processAIM($user, $data, $price){

        $note = isset($data['additional_data']['note'])?$data['additional_data']['note']:'';

        $this->paymentGateWay->amount   = $price;

        if($data['type'] == 'cc'){
            $this->paymentGateWay->card_num = $data['payment_data']['card_num'];
            $this->paymentGateWay->exp_date = $data['payment_data']['exp_date'];
        } elseif( $data['type'] == 'echeck'){
            $this->paymentGateWay->setECheck(
                $data['payment_data']['aba_number'],
                $data['payment_data']['acct_number'],
                $data['payment_data']['acct_type'],
                $data['payment_data']['bank_name'],
                $data['payment_data']['bank_name'],
                'WEB'
            );
        } else {
            throw new BadDataException("Wrong payment type");
        }

        $this->paymentGateWay->invoice_num = time();

        $description = $this->addAppsToPayment($data['subscriptions'], $user->role(), $data['period']);

        $this->paymentGateWay->description = $description;

        $this->paymentGateWay->setFields($data['required_data']);

        $this->paymentGateWay->setCustomFields($data['additional_data']);

        $response = $this->paymentGateWay->authorizeAndCapture();

        if($response->approved){

            if($data['coupon']){
                Coupon::disable($data['coupon']['code']);
            }

            $transaction_id = $response->transaction_id;

            $response = $this->prepareSubscriptionResponse($transaction_id, $price, $user['id'], $user->role(), $data['period'], $data['recurring'], $data['subscriptions'], $note);

            return $response;
        } else {
            $error_message = !empty($response->response_reason_text) ? $response->response_reason_text : $response->error_message;

            throw new AuthorizeException($error_message);
        }
    }

    private function processARB($user, $data, $price){

        $note = isset($data['additional_data']['note'])?$data['additional_data']['note']:'';

        $this->paymentGateWay->amount   = $price;

        $sub = new \AuthorizeNet_Subscription();

        $sub->amount = $price;

        if($data['type'] == 'cc'){
            $sub->creditCardCardNumber = $data['payment_data']['card_num'];
            $sub->creditCardExpirationDate = $data['payment_data']['exp_date'];
            //$sub->creditCardCardCode = $data['payment_data']['card_code'];
        } elseif( $data['type'] == 'echeck'){
            $sub->bankAccountNameOnAccount = $data['payment_data']['bank_name'];
            $sub->bankAccountRoutingNumber = $data['payment_data']['aba_number'];
            $sub->bankAccountAccountNumber = $data['payment_data']['acct_number'];
            $sub->bankAccountAccountType = strtolower($data['payment_data']['acct_type']);
            $sub->bankAccountBankName = $data['payment_data']['bank_name'];
            $sub->bankAccountEcheckType = "WEB";
        } else {
            throw new BadDataException("Wrong payment type");
        }

        $sub->startDate = Carbon::now()->day >= SubscriptionRepository::RECURRENT_DAY
            ? Carbon::now()->addMonth()->day(SubscriptionRepository::RECURRENT_DAY)->toDateString()
            : Carbon::now()->day(SubscriptionRepository::RECURRENT_DAY)->toDateString();
        $sub->intervalLength = "1";
        $sub->intervalUnit = "months";
        $sub->totalOccurrences = "9999";

        $sub->billToFirstName = $data['required_data']['first_name'];
        $sub->billToLastName = $data['required_data']['last_name'];
        $sub->billToAddress = $data['required_data']['address'];
        $sub->billToZip = $data['required_data']['zip'];
        $sub->billToState = $data['required_data']['state'];
        $sub->billToCountry = $data['required_data']['country'];
        $sub->billToCity = $data['required_data']['city'];
        $sub->customerPhoneNumber = $data['required_data']['phone'];

        $sub->name = "MissionNext network subscription";
        $sub->orderDescription = $this->addAppsToPayment($data['subscriptions'], $user->role(), $data['period']);

        $sub->customerId = $user['id'];

        if($data['coupon'] || $this->first_payment !== null){

            $trial_price = $this->first_payment === null ? 0 : $this->first_payment;

            if($data['coupon']){
                $trial_price -= $data['coupon']['value'];
            }

            $sub->trialOccurrences = 1;
            $sub->trialAmount = ($trial_price > 0)?$trial_price:0;
        }

        $arb = clone $this->recurringBilling;

        $response = $arb->createSubscription($sub);

        if($sub_id = $response->getSubscriptionId()){

            if($data['coupon']){
                Coupon::disable($data['coupon']['code']);
            }

            $response = $this->prepareSubscriptionResponse(0, $price, $user['id'], $user->role(), $data['period'], $data['recurring'], $data['subscriptions'], $note, $sub_id);

            return $response;
        } else {
            throw new AuthorizeException($response->getErrorMessage());
        }
    }

    /**
     * @return \AuthorizeNetARB
     */
    public function getRecurringBilling()
    {
        return $this->recurringBilling;
    }

    private function addAppsToPayment($sites, $role, $period){

        $description = '';

        foreach($sites as $site){

            $app = $this->apps[$site['id']];

            $price = $this->apps[$site['id']]['sub_configs'][$role][$site['partnership']]['price_'.$period];

            $this->paymentGateWay->addLineItem($site['id'], $app['name'], $app['name'] . " for " . $period, 1, $price, 'NO');

            $description .= $this->buildSiteDescription($app['name'], $role, $site['partnership'], $period, $price ) . ", ";
        }

        return $description;
    }

    private function buildSiteDescription($name, $role, $partnership, $period, $price){

        return sprintf("%s: %s%s - %s/$%s", $name, $role, $partnership?'/' . $partnership:'', $period, $price);
    }

    private function prepareApps($apps){

        $prepared = array();

        foreach($apps as $app){

            $papp = $app;

            $papp['configs'] = array();
            $papp['sub_configs'] = array(
                BaseDataModel::CANDIDATE => array(),
                BaseDataModel::AGENCY => array(),
                BaseDataModel::ORGANIZATION => array()
            );

            foreach($app['configs'] as $config){
                $papp['configs'][$config['key']] = $config['value'];
            }

            foreach($app['sub_configs'] as $sub_config){

                $papp['sub_configs'][$sub_config['role']][$sub_config['partnership']] = $sub_config;

            }

            $prepared[$papp['id']] = $papp;
        }

        return $prepared;
    }

    private function prepareSubscriptionResponse($transaction_id, $amount, $user_id, $role, $period, $recurrent, $sites, $comment = '', $subscription_id = 0){

        $response = array(
            'transaction' => $transaction_id ? array(
                'transaction_id' => $transaction_id,
                'comment' => $comment,
                'amount' => $amount
            ) : '',
            'subscriptions' => array()
        );

        $discount_on = count($sites) > 1;

        foreach($sites as $site){

            $response['subscriptions'][] = array(
                'app_id' => $site['id'],
                'user_id' => $user_id,
                'price' => $this->apps[$site['id']]['sub_configs'][$role][$site['partnership']]['price_'.$period],
                'paid' => $discount_on?$this->apps[$site['id']]['sub_configs'][$role][$site['partnership']]['price_'.$period]*((100 - $this->discount)/100):$this->apps[$site['id']]['sub_configs'][$role][$site['partnership']]['price_'.$period],
                'partnership' => $site['partnership'],
                'is_recurrent' => $recurrent,
                'period' => $period,
                'authorize_id' => $subscription_id,
                'renew_type' => $this->renew_type
            );
        }

        return $response;
    }

    private function calcPrice($sites, $role, $period, $type, $coupon = null, $renew_type){

        $data = array();
        $partnerships = array();

        foreach($sites as $site){
            $partnerships[$site['id']] = $site['partnership'];
            $data[$site['id']] = $this->apps[$site['id']];
        }

        $new_sites = array_diff_key($data, $this->defaults);
        $removed = array_diff_key($this->defaults, $data);
        $keeped = array_intersect_key($this->defaults, $data);

        $renew_price = 0;
        $new_price = 0;
        $old_price = 0;
        $site_number = 0;

        foreach($keeped as $id => $site){

            $site_number++;

            switch($renew_type){
                case 'k' : {
                    break;
                }
                case 't' : {
                    $renew_price += $this->apps[$id]['sub_configs'][$role][$partnerships[$id]]['price_year'] - $site['left_amount'];
                    break;
                }
                case 'e' : {
                    $renew_price += $this->apps[$id]['sub_configs'][$role][$partnerships[$id]]['price_year'];
                    break;
                }
                case 'm' : {
                    $old_price += min($site['left_amount'], $this->apps[$id]['sub_configs'][$role][$partnerships[$id]]['price_month']);
                    $renew_price += $this->apps[$id]['sub_configs'][$role][$partnerships[$id]]['price_month'];
                    break;
                }
            }

        }

        foreach($new_sites as $id => $site){

            $part_price = $site['sub_configs'][$role][$partnerships[$id]]['price_year'] * $this->part_multiplier;
            $site_number++;

            switch($renew_type){
                case 'k' : {
                    $new_price += $part_price;
                    break;
                }
                case 't' : {
                    $new_price += $this->apps[$id]['sub_configs'][$role][$partnerships[$id]]['price_year'];
                    break;
                }
                case 'e' : {
                    $new_price += $this->apps[$id]['sub_configs'][$role][$partnerships[$id]]['price_year'] + $part_price;
                    break;
                }
                case 'm' : {
                    $new_price += $this->apps[$id]['sub_configs'][$role][$partnerships[$id]]['price_month'];
                    break;
                }
            }

        }

        foreach($removed as $id => $site){

            switch($renew_type){
                case 'k' : {
                    $old_price += $site['left_amount'];
                    break;
                }
                case 't' : {
                    $old_price += $site['left_amount'];
                    break;
                }
                case 'e' : {
                    $old_price += $site['left_amount'];
                    break;
                }
                case 'm' : {
                    $old_price += $site['left_amount'];
                    break;
                }
            }

        }

        $total = 0;

        $compensation = $new_price - $old_price;
        $discount_on = $site_number > 1;
        $discount_percent = ( 100 - $this->discount ) / 100;

        if($renew_type == 'm'){

            $compensation = ($discount_on? $new_price * $discount_percent : $new_price) - $old_price;

            $total = $new_price + $renew_price;

            if($discount_on){
                $total = $total * $discount_percent;
            }

            $total += $this->fee;

            if($old_price > 0){
                $this->first_payment = $compensation + ($discount_on ? $renew_price * $discount_percent : $renew_price);

                if($coupon){
                    $this->first_payment -= $coupon['value'];
                }

                if($this->first_payment < 0){
                    $this->first_payment = 0;
                }

                $this->first_payment = round($this->first_payment);

                $this->first_payment += $this->fee;
            }

        } else {

            if($discount_on){
                $total += ($renew_price + $new_price) * $discount_percent;
            } else {
                $total += $renew_price + $new_price;
            }

            if($old_price > 0 && $new_price > 0){
                if($compensation > 0){
                    $total -= $old_price;
                } else {
                    $total -= $old_price + $compensation;
                }
            }

            if($coupon){
                $total -= $coupon['value'];
            }

            if($total < 0){
                $total = 0;
            }
        }

        return round($total);
    }

    private function getDefaults($user_id, $subsRepo){
        if ($subsRepo) {
            $_defaults = $subsRepo->userSubscriptions($user_id)->toArray();
        } else {
            $_defaults = $this->app->make(SubscriptionRepositoryInterface::class)->userSubscriptions($user_id)->toArray();
        }

        $defaults = array();

        $days_left = 0;
        $total_days = 0;

        foreach($_defaults as $default){

            $default['left_amount'] = round($default['paid'] * (  $default['days_left'] / ( (strtotime($default['end_date']) - strtotime($default['start_date'])) / (24*60*60) ) ));
            $defaults[$default['app_id']] = $default;

            if($default['days_left'] > $days_left){
                $days_left = $default['days_left'];
                if(!$default['is_recurrent']){
                    $total_days = round( (strtotime($default['end_date']) - strtotime($default['start_date'])) / (24*60*60));
                }
            }
        }

        if($total_days <= 0){
            $total_days = date('L')?366:365;
        }

        $this->part_multiplier = $total_days > 0 ? $days_left / $total_days : 0;

        return $defaults;
    }

    private function cancelAuthorizeSubs(){

        $ids = array();
        $subsForCancel = array();

        foreach($this->defaults as $default){
            if($default['authorize_id']){
                $ids[] = $default['authorize_id'];
                $subsForCancel[$default['authorize_id']] = [
                    'app_id'    => $default['app_id'],
                    'user_id'   => $default['user_id']
                ];
            }
        }

        $ids = array_unique($ids);

        foreach($ids as $id){
            $arb = clone $this->recurringBilling;
            $response = $arb->cancelSubscription($id);
            if (($response != null) && ($response->getResultCode() == "Ok")) {
                Subscription::where('app_id', $subsForCancel[$id]['app_id'])
                    ->where('user_id', $subsForCancel[$id]['user_id'])
                    ->update(['status' => Subscription::STATUS_CLOSED]);
            }
        }
    }

} 
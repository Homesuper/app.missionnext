<?php


namespace MissionNext\Controllers\Authorize;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use MissionNext\Models\Authorize\Renewal;
use MissionNext\Models\Subscription\Subscription;
use MissionNext\Models\Subscription\Transaction;

/**
 * Class Controller
 * @package MissionNext\Controllers\Authorize
 */
class Controller extends \Illuminate\Routing\Controller
{


    public function postIndex()
    {
        Log::info('Before Renewal Class', array('callback_data' => json_encode(Input::all())));

        $renewal = new Renewal(Input::all());

        if ($renewal->x_subscription_id) {

            Log::info('Monthly', array('callback_data' => var_export(Input::all(), true)));

        } else {
            Log::info('Annually', array('callback_data' => var_export(Input::all(), true)));

        }

        $subscriptionsQuery = Subscription::where('authorize_id', '=', $renewal->x_subscription_id)
            ->where('status', '<>', Subscription::STATUS_CLOSED);

        $oldSubs = $subscriptionsQuery->get();

        if (!$renewal->x_subscription_id || !$oldSubs->count()) {

            return;
        }

        Subscription::whereIn('id', $oldSubs->lists('id'))->update(['status' => Subscription::STATUS_CLOSED]);

        if ($renewal->isApproved()) {
            $subs = [];

            $oldSubs->each(function ($sub) use ($subs) {

                $notChangedData = array_only($sub->toArray(), ['partnership', 'price', 'user_id', 'app_id', 'comment', 'authorize_id', 'paid', 'is_recurrent']);
                $changedData = [
                    'start_date' => Carbon::now()->toDateTimeString(),
                    'end_date' => Carbon::now()->addMonth()->toDateTimeString(),
                    'status' => Subscription::STATUS_ACTIVE,
                ];
                $subData = array_merge($notChangedData, $changedData);
                $subs[] = Subscription::create($subData);

            });

            $subs = new Collection($subs);
            $transaction = new Transaction();
            $transactionData = [
                'amount' => $renewal->x_amount,
                'transaction_id' => $renewal->x_trans_id,
                'comment' => $renewal->x_description,
            ];
            $transaction = $transaction->create($transactionData);
            $transaction->subscriptions()->sync($subs->lists('id'));
        }

    }
} 
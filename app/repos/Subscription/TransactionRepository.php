<?php


namespace MissionNext\Repos\Subscription;


use Illuminate\Support\Collection;
use MissionNext\Models\Subscription\Transaction;
use MissionNext\Repos\AbstractRepository;

class TransactionRepository extends AbstractRepository implements TransactionRepositoryInterface
{
    protected $modelClassName = Transaction::class;


    /**
     * @return Transaction
     */
    public function getModel()
    {

        return $this->model;
    }

    /**
     * @param Collection $subscriptions
     * @param array $transaction
     *
     * @return Collection
     */
    public function syncWithSubscriptions(Collection $subscriptions, array $transaction)
    {
        if (!empty($transaction)) {
            $this->create($transaction);
            $this->getModel()->subscriptions()->sync($subscriptions->lists('id'));
        }

        return $subscriptions;
    }
} 
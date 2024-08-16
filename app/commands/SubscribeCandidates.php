<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use MissionNext\Api\Service\Payment\AuthorizeNet;
use MissionNext\Repos\Subscription\SubscriptionRepositoryInterface;
use MissionNext\Repos\RepositoryContainerInterface;
use Illuminate\Support\Facades\Config;

class SubscribeCandidates extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'subscription:update-candidates-subscriptions';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Added subscriptions for candidates migrated from old website.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $api_id = Config::get('app.api_login_id');
        $key = Config::get('app.transaction_key');
	    $app = new \Illuminate\Foundation\Application();
        $service = new AuthorizeNet( new \AuthorizeNetAIM($api_id,$key), new \AuthorizeNetARB($api_id,$key), $app);

        $helper = $this->getHelperSet();
        /** @var  $progress */
        $progress = $helper->get('progress');
        $progress->setFormat(\Symfony\Component\Console\Helper\ProgressHelper::FORMAT_NORMAL);
        $this->output->setDecorated(true);
        $this->info("Subscriptions create ...");

        /** @var  $repoContainer \MissionNext\Repos\RepositoryContainer */
        $repoContainer = $this->getLaravel()->make(RepositoryContainerInterface::class);

	    $subscriptionRepository = $repoContainer[SubscriptionRepositoryInterface::KEY];

		$users = \MissionNext\Models\User\User::join('user_roles', 'user_roles.user_id', '=', 'users.id')
            ->where('user_roles.role_id', 1)->get();

        $progCount = count($users);
        $progress->start($this->output, $progCount);

		foreach ($users as $user) {
		    $subscriptions = $user->subscriptions()->get();
		    if (0 == $subscriptions->count()) {

		        $activeApps = $user->appsStatuses()->get();
		        foreach ($activeApps as $app) {
		            if ($user->isActiveInApp($app)) {
		                $free = [];
                        $free[$app->id] = [
                            'id'            => $app->id,
                            'partnership'   => ''
                        ];

                        $recurring = true;

                        $data = array(
                            'user_id' => $user->id,
                            'recurring' => $recurring,
                            'period' => 'year',
                            'coupon' => '',
                            'subscriptions' => $free,
                            'type' => 'cc',
                            'renew_type' => 't'
                        );



                    }
                }
                $readyData = $service->processRequest($data, $subscriptionRepository);
                $subscriptionsData = $readyData['subscriptions'];
                $subscriptionRepository->saveMany($subscriptionsData);
            }
            $progress->advance();
        }

        $progress->finish();

        $this->comment("Subscriptions created successful.");

	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}

<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RemoveMatchingResultsForExpiredOrgs extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'matching:remove-matches-expired-organization';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Delete matching results for organization with expired subscription.';

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
		$expiredSubscriptions = \MissionNext\Models\Subscription\Subscription::where('status', \MissionNext\Models\Subscription\Subscription::STATUS_EXPIRED)->get();
		foreach ($expiredSubscriptions as $subscription) {
            $user = $subscription->user()->first();
            $app = $subscription->app()->first();
            $jobs = $user->jobs()->where('app_id', $app->id)->get();
            foreach ($jobs as $jobItem) {
                \MissionNext\Models\Matching\Results::where('user_type', \MissionNext\Models\DataModel\BaseDataModel::JOB)
                    ->where('user_id', $jobItem->id)
                    ->orWhere('for_user_type', \MissionNext\Models\DataModel\BaseDataModel::JOB)
                    ->where('for_user_id', $jobItem->id)->delete();
            }
            \MissionNext\Models\Matching\Results::where('app_id', $app->id)
                ->where(function($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->orWhere('for_user_id', $user->id);
                })->delete();

            $this->info('Matching results successfully deleted for user '.$user->id);
        }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
		);
	}

}

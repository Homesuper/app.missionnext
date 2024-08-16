<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RemoveMatchingResultsForInactiveOrganization extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'matching:remove-matches-inactive-organization';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Delete matching results for inactive organizations.';

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
        ini_set('memory_limit', '2048M');

	    $this->info('Delete matching results for organization.');
        $matchesForUser = \MissionNext\Models\Matching\Results::where('user_type', \MissionNext\Models\DataModel\BaseDataModel::ORGANIZATION)->get();
        foreach ($matchesForUser as $result) {
            $user = \MissionNext\Models\User\User::find($result->user_id);

            if ($user && !$user->isActiveInApp(\MissionNext\Models\Application\Application::find($result->app_id))) {
                $jobs = $user->jobs()->where('app_id', $result->app_id)->get();
                if ($jobs->count() > 0) {
                    foreach ($jobs as $jobItem) {
                        \MissionNext\Models\Matching\Results::where('user_type', \MissionNext\Models\DataModel\BaseDataModel::JOB)
                            ->where('user_id', $jobItem->id)
                            ->orWhere('for_user_type', \MissionNext\Models\DataModel\BaseDataModel::JOB)
                            ->where('for_user_id', $jobItem->id)->delete();
                    }
                }
                \MissionNext\Models\Matching\Results::where('app_id', $result->app_id)->
                                                    where('user_id', $result->user_id)->
                                                    where('for_user_id', $result->for_user_id)->delete();
                $this->info("Application id: ".$result->app_id);
                $this->info('Matching results successfully deleted for user '.$user->id);
            }
        }

        $matchesForUser = \MissionNext\Models\Matching\Results::where('for_user_type', \MissionNext\Models\DataModel\BaseDataModel::ORGANIZATION)->get();
        foreach ($matchesForUser as $result) {
            $user = \MissionNext\Models\User\User::find($result->for_user_id);
            if ($user && !$user->isActiveInApp(\MissionNext\Models\Application\Application::find($result->app_id))) {
                $jobs = $user->jobs()->where('app_id', $result->app_id)->get();
                foreach ($jobs as $jobItem) {
                    \MissionNext\Models\Matching\Results::where('user_type', \MissionNext\Models\DataModel\BaseDataModel::JOB)
                        ->where('user_id', $jobItem->id)
                        ->orWhere('for_user_type', \MissionNext\Models\DataModel\BaseDataModel::JOB)
                        ->where('for_user_id', $jobItem->id)->delete();
                }
                \MissionNext\Models\Matching\Results::where('app_id', $result->app_id)->
                                                where('user_id', $result->user_id)->
                                                where('for_user_id', $result->for_user_id)->delete();
                $this->info("Application id: ".$result->app_id);
                $this->info('Matching results successfully deleted for user '.$user->id);
            }
        }

        $this->info('Delete matching results for other user roles.');
        $matchesForUser = \MissionNext\Models\Matching\Results::where('for_user_type', \MissionNext\Models\DataModel\BaseDataModel::CANDIDATE)
                                                            ->orWhere('for_user_type', \MissionNext\Models\DataModel\BaseDataModel::AGENCY)->get();
        foreach ($matchesForUser as $result) {
            $user = \MissionNext\Models\User\User::find($result->for_user_id);
            if ($user && !$user->isActiveInApp(\MissionNext\Models\Application\Application::find($result->app_id))) {
                \MissionNext\Models\Matching\Results::where('app_id', $result->app_id)->
                                                where('user_id', $result->user_id)->
                                                where('for_user_id', $result->for_user_id)->delete();
                $this->info("Application id: ".$result->app_id);
                $this->info('Matching results successfully deleted for user '.$user->id);
            }
        }

        $this->info('Delete matching results for other user roles.');
        $matchesForUser = \MissionNext\Models\Matching\Results::where('user_type', \MissionNext\Models\DataModel\BaseDataModel::CANDIDATE)
                                                        ->orWhere('user_type', \MissionNext\Models\DataModel\BaseDataModel::AGENCY)->get();
        foreach ($matchesForUser as $result) {
            $user = \MissionNext\Models\User\User::find($result->user_id);
            if ($user && !$user->isActiveInApp(\MissionNext\Models\Application\Application::find($result->app_id))) {
                \MissionNext\Models\Matching\Results::where('app_id', $result->app_id)->
                                                where('user_id', $result->user_id)->
                                                where('for_user_id', $result->for_user_id)->delete();
                $this->info("Application id: ".$result->app_id);
                $this->info('Matching results successfully deleted for user '.$user->id);
            }
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

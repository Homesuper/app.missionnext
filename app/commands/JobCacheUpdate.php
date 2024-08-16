<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class JobCacheUpdate extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'profile-job:update-cache';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Refresh cache for job profiles.';

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
		$app_id = $this->option('app');

        ini_set('memory_limit', '1024M');

        /** @var  $repoContainer \MissionNext\Repos\RepositoryContainer */
        $repoContainer = $this->getLaravel()->make(\MissionNext\Repos\RepositoryContainerInterface::class);

        /** @var  $profileRepo  \MissionNext\Repos\User\ProfileRepositoryFactory */
        $profileRepo = $repoContainer[\MissionNext\Repos\User\ProfileRepositoryFactory::KEY];

        /** @var  $securityContext \MissionNext\Api\Auth\SecurityContext */
        $securityContext = \MissionNext\Facade\SecurityContext::getFacadeRoot();

        $helper = $this->getHelperSet();
        /** @var  $progress */
        $progress = $helper->get('progress');
        $progress->setFormat(\Symfony\Component\Console\Helper\ProgressHelper::FORMAT_NORMAL);
        $this->output->setDecorated(true);
        $this->info("Jobs update ...");

        if (!empty($app_id)) {
            $jobs = \MissionNext\Models\Job\Job::where('app_id', $app_id)->lists('id');
        } else {
            $jobs = \MissionNext\Models\Job\Job::lists('id');
        }

        $progCount = count($jobs);
        $progress->start($this->output, $progCount);

        foreach ($jobs as $jobId) {
            $job = \MissionNext\Models\Job\Job::find($jobId);
            if ($job) {
                $securityContext->getToken()->setRoles([$job->role()]);
                $repoContainer->setSecurityContext($securityContext);
                $profileRepo->setRepoContainer($repoContainer);
                $profileRepo->profileRepository()->addUserCachedData($job);

                $progress->advance();
            }
        }

        $progress->finish();

        $this->comment("Update Successful");
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('app', null, InputOption::VALUE_OPTIONAL, 'Application id.', null),
		);
	}

}

<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\ProgressHelper;
use MissionNext\Repos\RepositoryContainerInterface;

class UnlinkUserApps extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'unlink:users';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Remove all apps for manually added users.';

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
		ini_set('memory_limit', '1024M');

		/** @var  $repoContainer \MissionNext\Repos\RepositoryContainer */
		$repoContainer = $this->getLaravel()->make(RepositoryContainerInterface::class);

		/** @var  $profileRepo  \MissionNext\Repos\User\ProfileRepositoryFactory */
		$profileRepo = $repoContainer[\MissionNext\Repos\User\ProfileRepositoryFactory::KEY];

		/** @var  $securityContext \MissionNext\Api\Auth\SecurityContext */
		$securityContext = \MissionNext\Facade\SecurityContext::getFacadeRoot();

		$helper = $this->getHelperSet();
		/** @var  $progress */
		$progress = $helper->get('progress');
		$progress->setFormat(ProgressHelper::FORMAT_NORMAL);
		$this->output->setDecorated(true);
		$this->info("Profile update ...");

		$users = \MissionNext\Models\User\User::whereNotNull('old_id')->get();
		$progCount = $users->count();

		$progress->start($this->output, $progCount);

		foreach ($users as $user) {
			$userApps = $user->apps()->get();
			foreach($userApps as $app) {
				$user->removeApp($app);
			}

			$securityContext->getToken()->setRoles([$user->role()]);
			$repoContainer->setSecurityContext($securityContext);
			$profileRepo->setRepoContainer($repoContainer);
			$profileRepo->profileRepository()->addUserCachedData($user);

			$progress->advance();
		}

		$progress->finish();

		$this->comment("Users deactivated on applications.");
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [];
	}

}

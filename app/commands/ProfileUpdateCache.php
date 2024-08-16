<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use MissionNext\Repos\RepositoryContainerInterface;
use Symfony\Component\Console\Helper\ProgressHelper;

class ProfileUpdateCache extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'profile:update-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update user cache.';

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
        $role = $this->option('role');
        $roles = [
            'candidate' => 1,
            'organization' => 2,
            'agency'   => 3
        ];

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
        if (!empty($role) && key_exists($role, $roles)) {
            $users = \MissionNext\Models\User\User::join('user_roles', 'users.id', '=','user_roles.user_id')
                ->where('user_roles.role_id', $roles[$role])->lists('id');
        } else {
            $users = \MissionNext\Models\User\User::lists('id');
        }

        $progCount = count($users);
        $progress->start($this->output, $progCount);

        foreach ($users as $userId) {
            $user = \MissionNext\Models\User\User::find($userId);
            if ($user) {
                $securityContext->getToken()->setRoles([$user->role()]);
                $repoContainer->setSecurityContext($securityContext);
                $profileRepo->setRepoContainer($repoContainer);
                $profileRepo->profileRepository()->addUserCachedData($user);

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
            array('role', null, InputOption::VALUE_OPTIONAL, 'User role for cache update.', null),
        );
    }

}

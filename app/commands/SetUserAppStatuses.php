<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use MissionNext\Repos\RepositoryContainerInterface;
use Symfony\Component\Console\Helper\ProgressHelper;

class SetUserAppStatuses extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'profile:set-app-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate user app statuses';

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
        $apps = \MissionNext\Models\Application\Application::all()->lists('id');
        /** @var  $user \MissionNext\Models\User\User */
        foreach(\MissionNext\Models\User\User::all() as $user){
            $user->appsStatuses()->sync($apps);
        }

    }

//	/**
//	 * Get the console command arguments.
//	 *
//	 * @return array
//	 */
//	protected function getArguments()
//	{
//		return array(
//			array('example', InputArgument::REQUIRED, 'An example argument.'),
//		);
//	}

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
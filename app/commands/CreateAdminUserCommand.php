<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateAdminUserCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'adminuser:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create Admin user (using Sentry)';

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
		$username = $this->option('username');
        $password = $this->option('password');
        $email = $this->option('email');

        $this->info('username is '.$username);
        $this->info('password is '.$password);
        $this->info('e-mail is '.$email);
        if ($this->confirm('You confirm create user (y/n)?')) {

            $user = Sentry::createUser(array(
                'username'  => $username,
                'password'  => $password,
                'email'     => $email,
                'activated' => true,
            ));

            if($user) {
                $this->info('user created successfully');
            } else {
                $this->error('the user has not created');
            }

        }
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('username', null, InputOption::VALUE_OPTIONAL, 'Username (login).', null),
			array('password', null, InputOption::VALUE_OPTIONAL, 'password.', null),
			array('email', null, InputOption::VALUE_OPTIONAL, 'email.', null),
		);
	}

}

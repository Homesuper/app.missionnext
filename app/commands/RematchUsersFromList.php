<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RematchUsersFromList extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'matching:match-from-list';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command starts matching of the users that saved profile during current day.';

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
	    $rematchRecords = DB::table('queue_users_list')->get();

	    foreach ($rematchRecords as $record) {
            \MissionNext\Api\Service\Matching\Queue\Master\ProfileUpdateMatching::run([
                "userId"=> $record->user_id,
                "appId"=> $record->app_id,
                "role" => $record->role
            ]);

            DB::table('queue_users_list')
                ->where('user_id', $record->user_id)
                ->where('app_id', $record->app_id)
                ->where('role', $record->role)->delete();

        }

		$this->comment('All records added to matching process.');
	}

}

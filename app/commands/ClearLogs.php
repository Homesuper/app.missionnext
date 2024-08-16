<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ClearLogs extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'logs:clear';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Delete logs files older 10 days.';

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
	    $storage_path = storage_path();
	    $plugin_logs_path = $storage_path.'/../../../'.\Illuminate\Support\Facades\Config::get('app.wordpress_logs');

        $date = date_create("-10 days");

        $log_file_template = $date->format('Y-m-d');

        if (file_exists($storage_path.'/logs/custom_logs/job/'.$log_file_template.'.txt')) {
            unlink($storage_path.'/logs/custom_logs/job/'.$log_file_template.'.txt');
        }

        if (file_exists($storage_path.'/logs/custom_logs/user/'.$log_file_template.'.txt')) {
            unlink($storage_path.'/logs/custom_logs/user/'.$log_file_template.'.txt');
        }

        if (file_exists($storage_path.'/logs/custom_logs/mail/'.$log_file_template.'.txt')) {
            unlink($storage_path.'/logs/custom_logs/mail/'.$log_file_template.'.txt');
        }

        $hours = array('00', '01', '02', '03', '04', '05', '06', '07', '08', '09');
        foreach ($hours as $hourItem) {
            if (file_exists($storage_path.'/logs/custom_logs/query/'.$log_file_template.' '.$hourItem.'.txt')) {
                unlink($storage_path.'/logs/custom_logs/query/'.$log_file_template.' '.$hourItem.'.txt');
            }
        }

        for ($hour = 10; $hour < 24; $hour++) {
            if (file_exists($storage_path.'/logs/custom_logs/query/'.$log_file_template.' '.$hour.'.txt')) {
                unlink($storage_path.'/logs/custom_logs/query/'.$log_file_template.' '.$hour.'.txt');
            }
        }

        if (file_exists($storage_path.'/logs/log-cgi-fcgi-'.$log_file_template.'.txt')) {
            unlink($storage_path.'/logs/log-cgi-fcgi-'.$log_file_template.'.txt');
        }

        if (file_exists($storage_path.'/logs/log-cli-'.$log_file_template.'.txt')) {
            unlink($storage_path.'/logs/log-cli-'.$log_file_template.'.txt');
        }

        if (file_exists($plugin_logs_path.'/logs_'.$log_file_template.'.txt')) {
            unlink($plugin_logs_path.'/logs_'.$log_file_template.'.txt');
        }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}

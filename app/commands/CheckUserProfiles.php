<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CheckUserProfiles extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'profile:check-completed-profile';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Check user\'s completed that finished.';

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
	    $roles = [
	        'candidate',
            'organization',
            'agency'
        ];

        \Illuminate\Support\Facades\DB::table('user_profile_completed')->delete();

        ini_set('memory_limit', '1024M');

        /** @var  $repoContainer \MissionNext\Repos\RepositoryContainer */
        $repoContainer = $this->getLaravel()->make(\MissionNext\Repos\RepositoryContainerInterface::class);

        $fieldRepo = $this->getLaravel()->make(\MissionNext\Repos\Field\FieldRepositoryInterface::class);

        /** @var  $securityContext \MissionNext\Api\Auth\SecurityContext */
        $securityContext = \MissionNext\Facade\SecurityContext::getFacadeRoot();

        $helper = $this->getHelperSet();
        /** @var  $progress */
        $progress = $helper->get('progress');
        $progress->setFormat(\Symfony\Component\Console\Helper\ProgressHelper::FORMAT_NORMAL);
        $this->output->setDecorated(true);
        $this->info("Check profiles ...");

        $apps = \MissionNext\Models\Application\Application::get();
        $newApp = [];
        foreach ($apps as $application) {
            $newApp[$application->id] = $application;
        }

        $apps = $newApp;
        $forms = [];
        foreach ($apps as $app) {
            foreach ($roles as $role) {
                $securityContext->getToken()->setRoles([$role]);
                $securityContext->getToken()->setApp($app);
                $repoContainer->setSecurityContext($securityContext);
                $forms[$app->id][$role] = $this->getProfileForm($app, $repoContainer);
            }
        }

        $users = \MissionNext\Models\User\User::get()->lists('id');
        $usersCount = count($users);
        $progress->start($this->output, $usersCount);
        $cached_users_group = [];

        foreach ($roles as $role) {
            $cached_users_group[$role] = \Illuminate\Support\Facades\DB::table($role."_cached_profile")->select('data')->whereIn('id', $users)->get();
        }

        $customizedProfile = null;
        foreach ($cached_users_group as $group) {
            foreach ($group as $userItem) {
                $decodedData = json_decode($userItem->data, true);
                foreach ($decodedData['app_ids'] as $app_id) {
                    $profileUnvalid = false;
                    $profileData = $this->getPreparedProfile($decodedData['profileData'], $forms[$app_id][$decodedData['role']]);
                    if (count($profileData) > 0) {
                        $fieldNames = array_keys($profileData);

                        $securityContext->getToken()->setRoles([$decodedData['role']]);
                        $securityContext->getToken()->setApp($apps[$app_id]);
                        $repoContainer->setSecurityContext($securityContext);
                        $fieldRepo->setRepoContainer($repoContainer);

                        /** @var  $fields Collection */
                        $fields = $fieldRepo->modelFields()->whereIn('symbol_key', $fieldNames)->get();

                        if ($fields->count() !== count($fieldNames)) {
                            $profileUnvalid = true;
                        }

                        $constraints = [];
                        $validationData = [];

                        foreach ($fields as $field) {
                            if (isset($profileData[$field->symbol_key])) {
                                $validationData[$field->symbol_key] = $profileData[$field->symbol_key]['value'];//@TODO can be array
                                if ($field->pivot->constraints) {
                                    $constraints[$field->symbol_key] = $field->pivot->constraints;
                                } else {
                                    $constraints[$field->symbol_key] = '';
                                }
                            }
                        }

                        /** @var  $validator \Illuminate\Validation\Validator */
                        $validator = \Illuminate\Support\Facades\Validator::make(
                            $validationData,
                            $constraints
                        );

                        if ($validator->fails()) {
                            $profileUnvalid = true;
                        }
                    } else {
                        $profileUnvalid = true;
                    }

                    if (!$profileUnvalid) {
                        $checkRecord = \Illuminate\Support\Facades\DB::table('user_profile_completed')
                            ->where('user_id', $decodedData['id'])
                            ->where('app_id', $app_id)->first();

                        if (!$checkRecord) {
                            \Illuminate\Support\Facades\DB::table('user_profile_completed')->insert([
                                'user_id' => $decodedData['id'],
                                'app_id' => $app_id,
                                'role'  => $decodedData['role'],
                                'completed' => true
                            ]);
                        }
                    }
                }
                $progress->advance();
            }
        }

        $progress->finish();

        $this->comment("Check finished.");
	}

	private function getPreparedProfile($profile, $form) {
	    $form = $this->optimizeFormFields($form);

        $profileArray = [];
        foreach ($profile as $key => $field) {
            if (isset($form[$key])) {
                $profileArray[$key]['value'] = $field;
                $profileArray[$key]['type'] = $form[$key]['type'];
                $profileArray[$key]['constraints'] = $form[$key]['constraints'];
            }
        }

        return $profileArray;
    }

    private function optimizeFormFields($form) {
	    $formFieldsArray = [];

        if ($form) {
            foreach ($form as &$group) {
                foreach ($group['fields'] as &$field) {
                    unset($field['meta']);
                    unset($field['model_meta']);
                    unset($field['choices']);

                    $formFieldsArray[$field['symbol_key']] = $field;
                }
            }
        }

        return $formFieldsArray;
    }

	private function getProfileForm($app, $repoContainer) {

        /** @var  $dm AppDataModel */
        $dm = $app->DM();

        $forms = $dm->forms()->whereSymbolKey('profile')->get();

        /** @var  $form AppForm */
        $form = $forms->first();

        if (!$form || !$form->fields()->count()) {

            return null;
        }
        /** @var  $formRepo FormRepository */
        $formRepo = $repoContainer[\MissionNext\Repos\Form\FormRepositoryInterface::KEY];

        return $formRepo->structuredGroupFields($form);
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

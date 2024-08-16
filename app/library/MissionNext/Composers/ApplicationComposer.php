<?php


namespace MissionNext\Composers;


use MissionNext\Models\Application\Application;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\Subscription\Partnership;
use MissionNext\Provider\ComposerProvider;

/**
 * Class ApplicationComposer
 * @package MissionNext\Composers
 * @see ComposerProvider
 */
class ApplicationComposer
{
    public function compose($view)
    {
        $view->with('applications', Application::all()->lists('name','id') );
        $view->with('roles',
                 array(
                     BaseDataModel::CANDIDATE => "Candidate",
                     BaseDataModel::ORGANIZATION => "Organization",
                     BaseDataModel::AGENCY => "Agency",
                     ) );

        $view->with('partnerships',
            [
              Partnership::LIMITED => "Limited",
              Partnership::BASIC => "Basic",
              Partnership::PLUS => "Plus",
            ]
            );
    }
} 
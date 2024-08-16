<?php

namespace MissionNext\Provider;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use MissionNext\Api\Auth\SecurityContextResolver;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Repos\Affiliate\AffiliateRepository;
use MissionNext\Repos\Affiliate\AffiliateRepositoryInterface;
use MissionNext\Repos\CachedData\UserCachedRepository;
use MissionNext\Repos\CachedData\UserCachedRepositoryInterface;
use MissionNext\Repos\Field\FieldRepository;
use MissionNext\Repos\Field\FieldRepositoryInterface;
use Illuminate\Support\Facades\App;
use MissionNext\Repos\Form\FormRepository;
use MissionNext\Repos\Form\FormRepositoryInterface;
use MissionNext\Repos\FormGroup\FormGroupRepository;
use MissionNext\Repos\FormGroup\FormGroupRepositoryInterface;
use MissionNext\Repos\Inquire\InquireRepository;
use MissionNext\Repos\Inquire\InquireRepositoryInterface;
use MissionNext\Repos\Matching\ConfigRepository;
use MissionNext\Repos\Matching\ConfigRepositoryInterface;
use MissionNext\Repos\Matching\ResultsRepository;
use MissionNext\Repos\Matching\ResultsRepositoryInterface;
use MissionNext\Repos\RepositoryContainer;
use MissionNext\Repos\RepositoryContainerInterface;
use MissionNext\Repos\Subscription\SubConfigRepository;
use MissionNext\Repos\Subscription\SubConfigRepositoryInterface;
use MissionNext\Repos\Subscription\SubscriptionRepository;
use MissionNext\Repos\Subscription\SubscriptionRepositoryInterface;
use MissionNext\Repos\Subscription\TransactionRepository;
use MissionNext\Repos\Subscription\TransactionRepositoryInterface;
use MissionNext\Repos\User\JobRepository;
use MissionNext\Repos\User\JobRepositoryInterface;
use MissionNext\Repos\User\ProfileRepositoryFactory;
use MissionNext\Repos\User\UserRepository;
use MissionNext\Repos\User\UserRepositoryInterface;
use MissionNext\Repos\ViewField\ViewFieldRepository;
use MissionNext\Repos\ViewField\ViewFieldRepositoryInterface;
use MissionNext\Repos\Languages\LanguageRepositoryInterface;
use MissionNext\Repos\Languages\LanguageRepository;
use MissionNext\Repos\Translation\FieldRepositoryInterface as TransFieldRepoInterface;
use MissionNext\Repos\Translation\FieldRepository as TransFieldRepo;

class RepositoryProvider extends ServiceProvider
{

    public function register()
    {

        App::bind(FieldRepositoryInterface::class, function ($app) {

            //@TODO based on condition return different model Repository
            return (new SecurityContextResolver(new FieldRepository()))->getResolvedObject();
        });

        App::bind(UserRepositoryInterface::class, function () {
            return (new SecurityContextResolver(new UserRepository()))->getResolvedObject();
        });

        App::bind(ViewFieldRepositoryInterface::class, function () {

            return new ViewFieldRepository();
        });

        App::bind(FormRepositoryInterface::class, function () {

            return new FormRepository();
        });

        App::bind(FormGroupRepositoryInterface::class, function () {

            return (new SecurityContextResolver(new FormGroupRepository()))->getResolvedObject();
        });

        App::bind(JobRepositoryInterface::class, function () {

            return (new SecurityContextResolver(new JobRepository()))->getResolvedObject();
        });

        App::bind(ConfigRepositoryInterface::class, function () {

            return (new SecurityContextResolver(new ConfigRepository()))->getResolvedObject();
        });

        App::bind(ResultsRepositoryInterface::class, function () {

            return new ResultsRepository();
        });

        App::bind(InquireRepositoryInterface::class, function () {

            return (new SecurityContextResolver(new InquireRepository()))->getResolvedObject();
        });

        App::bind(AffiliateRepositoryInterface::class, function () {

            return new AffiliateRepository();
        });

        App::bind(UserCachedRepositoryInterface::class, function () {

            return new UserCachedRepository();
        });

        App::bind(LanguageRepositoryInterface::class, function () {

            return new LanguageRepository();
        });

        App::bind(TransFieldRepoInterface::class, function () {

            return new TransFieldRepo();
        });

        App::bind(ProfileRepositoryFactory::class, function ($app) {

            return new ProfileRepositoryFactory();
        });

        App::bind(SubConfigRepositoryInterface::class, function ($app) {

            return new SubConfigRepository();
        });

        App::bind(SubscriptionRepositoryInterface::class, function ($app) {

            return new SubscriptionRepository();
        });

        App::bind(TransactionRepositoryInterface::class, function ($app) {

            return new TransactionRepository();
        });

        //REPO CONTAINER
        App::bind(RepositoryContainerInterface::class, function(Application $app)
        {

            return (new SecurityContextResolver( new RepositoryContainer($app)))->getResolvedObject();
        });

    }

} 
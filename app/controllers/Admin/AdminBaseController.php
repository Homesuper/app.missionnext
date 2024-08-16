<?php
namespace MissionNext\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Session\SessionManager;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Form;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Cartalyst\Sentry\Users\LoginRequiredException as LoginRequired;
use Cartalyst\Sentry\Users\PasswordRequiredException as PasswordRequired;
use Cartalyst\Sentry\Users\WrongPasswordException as WrongPass;
use Cartalyst\Sentry\Users\UserNotFoundException as UserNotFound;
use Cartalyst\Sentry\Users\UserNotActivatedException as UserNotActivated;
use Cartalyst\Sentry\Throttling\UserSuspendedException as UserSuspended;
use Cartalyst\Sentry\Throttling\UserBannedException as UserBanned;
use Cartalyst\Sentry\Users\UserExistsException as UserExist;
use Cartalyst\Sentry\Users\UserAlreadyActivatedException as UserAlreadyActivated;
use Illuminate\View\Factory;
use MissionNext\Api\Service\Payment\PaymentGatewayInterface;
use MissionNext\Filter\RouteSecurityFilter;
use MissionNext\Repos\RepositoryContainerInterface;
use MissionNext\Controllers\traits\Controller as SecurityTraits;
use Cartalyst\Sentry\Sentry as MainSentry;

use Illuminate\Support\Facades\Queue;
use MissionNext\Models\Matching\Results;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Facade\SecurityContext;
use MissionNext\Api\Auth\SecurityContext as SC;
use MissionNext\Models\Subscription\Partnership;
use MissionNext\Models\Subscription\Subscription;
use MissionNext\Api\Service\DataTransformers\UserCachedDataStrategy;
use MissionNext\Api\Service\DataTransformers\UserCachedTransformer;
use MissionNext\Api\Service\Matching\TransData;
use MissionNext\Api\Service\Matching\Queue\Master\ProfileUpdateMatching;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;


class AdminBaseController extends Controller
{

    use SecurityTraits;

    const PAGINATE = 30;

    const VIEW_PREFIX = '';
    const ROUTE_PREFIX = '';

    protected $request;
    protected $repoContainer;
    /** @var \Illuminate\View\Factory */
    protected $view;

    protected $redirect;
    protected $session;

    /** @var \MissionNext\Api\Service\Payment\AuthorizeNet */
    protected $paymentGateway;

    /** @var  MainSentry */
    protected $sentry;


    public function __construct(PaymentGatewayInterface $paymentGateway, Store $session, Redirector $redirector, Request $request, RepositoryContainerInterface $containerInterface, Factory $viewFactory)
    {
        //$this->beforeFilter('csrf', array('on'=>'post'));
        $this->beforeFilter(RouteSecurityFilter::ROLE_ADMIN_AREA);
        $this->sentry = Sentry::getFacadeRoot();
        $this->request = $request;
        $this->repoContainer = $containerInterface;
        $this->view = $viewFactory;
        $this->redirect = $redirector;
        $this->session = $session;
        $this->paymentGateway = $paymentGateway;
    }


    /**
     *
     * @return \Illuminate\View\View
     */
    public function login()
    {
        if ($this->request->isMethod('post')) {
            Input::flash();
            $input = Input::only('username', 'password');
            $rules = array(
                'username' => 'required|min:3|max:200',
                'password' => 'required|min:4'
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {

                return Redirect::route('login')->withInput()->withErrors($validator);
            }
            try {
                $user = Sentry::authenticate($input, false);

                return Redirect::route('adminHomepage');
            } catch (LoginRequired $e) {
                Session::flash('info', 'Login field is required.');
            } catch (PasswordRequired $e) {
                Session::flash('info', 'Password field is required.');
            } catch (WrongPass $e) {
                Session::flash('info', 'Wrong password, try again.');
            } catch (UserNotFound $e) {
                Session::flash('info', 'User was not found.');
            } catch (UserNotActivated $e) {
                Session::flash('info', 'User is not activated.');
            } // The following is only required if the throttling is enabled
            catch (UserSuspended $e) {
                Session::flash('info', 'User is suspended.');
            } catch (UserBanned $e) {
                Session::flash('info', 'User is banned.');
            }
        }

        return View::make('admin.loginForm');
    }

    /**
     * @param $name
     *
     * @return string
     */
    protected function viewTemplate($name)
    {
        return static::VIEW_PREFIX . ".{$name}";
    }

    /**
     * @param $name
     *
     * @return string
     */
    protected function routeName($name)
    {

        return static::ROUTE_PREFIX . ".{$name}";
    }

    protected function logger($log_type, $action, $message){
        $view_log = new Logger('View Logs');
        $view_log->pushHandler(new StreamHandler(storage_path().'/logs/custom_logs/'. $log_type.'/'. date('Y-m-d').'.txt', Logger::INFO));
        $view_log->info('=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=');
        $view_log->info('Action: '. $action);
        $view_log->addInfo($message);
        $view_log->info('=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=');
    }


    public function callAction($method, $parameters)
	{
    	return parent::callAction($method, array_values($parameters));
	}
}
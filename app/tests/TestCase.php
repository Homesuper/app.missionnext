<?php
use Mockery as m;
use MissionNext\Facade\SecurityContext as FS;
use MissionNext\Api\Auth\SecurityContext as SC;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{

    protected $useDatabase = true;

    protected $routePrefix = '';

    protected $applicationKey = '123456';
    protected $applicationPrivateKey = '654321';

    /**
     * @param $method
     * @param $uri
     * @param array $parameters
     * @param array $urlParams
     * @param array $files
     * @param null $content
     * @return \MissionNext\Api\Response\RestResponse
     */
    public function authorizedCall($method, $uri, array $parameters = [], array $urlParams = [], array $files = [], $content = null)
    {
        $currentTimestamp = time();
        $urlParams['timestamp'] = $currentTimestamp;
        $query = http_build_query($urlParams);

        $uri = $this->routePrefix.'/'.$uri.'?'.$query;

        $hash = strtr(base64_encode(
            hash_hmac('sha1', '/'.$uri,
                base64_decode(strtr($this->applicationPrivateKey, '-_', '+/')), true)), '+/', '-_');

        $server = ['HTTP_X-Auth'=>$this->applicationKey,'HTTP_X-Auth-Hash' => $hash];

        $this->client->request( $method, $uri, $parameters, $files, $server, $content);

        return $this->client->getResponse();
    }

    /**
     * Creates the application.
     *
     * @return Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        $unitTesting = true;

        $testEnvironment = 'testing';

        return require __DIR__ . '/../../bootstrap/start.php';
    }

    public function setUp()
    {
        parent::setUp();
        if ($this->useDatabase) {
            $this->setUpDb();
        }
        $this->setApp();
    }

    public function teardown()
    {
        m::close();
    }

    protected function setApp()
    {
      //@TODO Authorezid request
        $this->app['router']->enableFilters();
        $this->app->register(\MissionNext\Provider\RoutingProvider::class);
        $this->app->register(\MissionNext\Provider\SecurityProvider::class);
        $this->app->register(\MissionNext\Provider\ErrorProvider::class);
        $this->app->register(\MissionNext\Provider\RepositoryProvider::class);
        /** @var  $securityContext \MissionNext\Api\Auth\SecurityContext */

        $this->routePrefix = \MissionNext\Routing\Routing::API_PREFIX;
    }



    public function setUpDb()
    {
        Artisan::call('migrate', array('--env' => 'testing'));
        Artisan::call('db:seed', array('--env' => 'testing'));
       // Artisan::call('db:seed', array('--class' => 'TestDatabaseSeeder'));

    }

    public function teardownDb()
    {
        Artisan::call('migrate:reset', ['--env' => 'testing']);
    }

}

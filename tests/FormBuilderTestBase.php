<?php

require_once('TestController.php');
require_once('TestEntity.php');

use A2design\Form\FormBuilder;

/**
 * Class FormBuilderTestBase
 *
 * Parent class for tests with mock setting and laravel test environment including
 */
abstract class FormBuilderTestBase extends Orchestra\Testbench\TestCase
{
    /**
     * @var \A2design\Form\FormBuilder
     */
    protected $formBuilder;

    /**
     * Mock of \Illuminate\View\Factory
     *
     * @var \Mockery\MockInterface|\Illuminate\View\Factory
     */
    protected $viewFactory;

    /**
     * Mock of \Illuminate\Session\Store
     *
     * @var \Mockery\MockInterface|\Illuminate\Session\Store
     */
    protected $session;

    /**
     * Mock of \Illuminate\Config\Repository
     *
     * @var \Mockery\MockInterface|\Illuminate\Config\Repository
     */
    protected $config;

    /**
     * Mock of \Illuminate\Routing\RouteCollection
     *
     * @var \Mockery\MockInterface|\Illuminate\Routing\RouteCollection
     */
    protected $routes;

    /**
     * Mock of \Illuminate\Http\Request
     *
     * @var \Mockery\MockInterface|\Illuminate\Http\Request
     */
    protected $request;

    /**
     * Mock of TestModel
     *
     * @var \Mockery\MockInterface|TestEntity
     */
    protected $model;

    /**
     * View errors
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $errorBag;

    /**
     * Specify the package service provider
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return ['A2design\Form\FormServiceProvider'];
    }

    /**
     * Specify the package aliases
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Form' => 'A2design\Form\FormFacade',
        ];
    }

    /**
     * Setup the test environment initially.
     */
    public function setUp()
    {
        parent::setUp();

        $this->config = Mockery::mock(\Illuminate\Config\Repository::class);
        $this->stubConfig();

        $this->viewFactory = Mockery::mock(\Illuminate\Contracts\View\Factory::class);
        $this->stubViewFactory();

        $this->routes = Mockery::mock(\Illuminate\Routing\RouteCollection::class);
        $this->createRoutes();

        $this->request = Mockery::mock(\Illuminate\Http\Request::class);
        $this->stubRequest();

        $this->session = Mockery::mock(\Illuminate\Session\Store::class);
        $this->stubSession();

        $this->model = Mockery::mock(TestEntity::class);
        $this->stubModel();

        $this->formBuilder = new FormBuilder(
            $this->viewFactory,
            $this->session,
            $this->routes,
            $this->config,
            $this->request
        );
    }

    /**
     * Destroy the test environment at the end.
     */
    public function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    /**
     * Create new route
     *
     * @param \Mockery\MockInterface $routesMock Mock for route collection
     * @param string $method
     * @param bool $hasName
     */
    protected function addRoute($routesMock, $method, $hasName = true)
    {
        $controller = new TestController();
        /** @var \Illuminate\Routing\Router $router */
        $router = app()->make(\Illuminate\Routing\Router::class);

        $withoutNamePostfix = '';

        if (!$hasName) {
            $withoutNamePostfix = '-without-route-name';
        }

        $url = '/' . $method . '-url' . kebab_case($withoutNamePostfix);
        $action = 'TestController@' . $method . studly_case($withoutNamePostfix);
        $actionPrefix = $this->getFromConfig('route_name_space');
        $fullAction = $actionPrefix . '\\' . $action;

        $route = $router->$method($url, $action);
        $routeMock = Mockery::mock($route);
        $routeMock->shouldReceive('getController')->andReturn($controller)->byDefault();

        if ($hasName) {
            $name = $method . 'RouteName';
            $route->name($name);
            $routesMock->shouldReceive('getByName')->with($name)->andReturn($routeMock)->byDefault();
        }

        $routesMock->shouldReceive('getByAction')->with($fullAction)->andReturn($routeMock)->byDefault();
    }

    /**
     * Set initial config values for the config mock
     */
    protected function stubConfig()
    {
        $configMock = $this->config;

        $configMock->shouldReceive('get')
            ->withAnyArgs()
            ->andReturn(null)
            ->byDefault();

        $configMock->shouldReceive('has')
            ->withAnyArgs()
            ->andReturn(false)
            ->byDefault();

        /** @var \Illuminate\Config\Repository $configs */
        $configs = config();
        $configName = FormBuilder::CONFIG_NAME;

        foreach ($configs->all()[$configName] as $name => $value) {

            $this->setConfigValueStub($name, $value);
        }
    }

    /**
     * Set some value for config mock
     * Don't forget to reset after temporary adding
     *
     * @param $name
     * @param $value
     */
    protected function setConfigValueStub($name, $value)
    {
        $configMock = $this->config;
        $configName = FormBuilder::CONFIG_NAME;

        $configMock->shouldReceive('get')
            ->with($configName . '.' . $name)
            ->andReturn($value)
            ->byDefault();

        $configMock->shouldReceive('has')
            ->with($configName . '.' . $name)
            ->andReturn(true)
            ->byDefault();
    }

    /**
     * Set initial values for config
     */
    protected function resetConfig()
    {
        $this->stubConfig();
    }

    /**
     * Set methods values for the model
     */
    protected function stubModel()
    {
        $modelMock = $this->model;

        $modelMock->shouldReceive('getKey')
            ->withAnyArgs()
            ->andReturn('id')
            ->byDefault();
    }

    /**
     * Make routes for all methods
     */
    protected function createRoutes()
    {
        $routesMock = $this->routes;

        $routesMock->shouldReceive('getByName')->withAnyArgs()->andReturnNull()->byDefault();
        $routesMock->shouldReceive('getByAction')->withAnyArgs()->andReturnNull()->byDefault();

        $methods = ['get', 'post', 'patch', 'put', 'delete'];

        foreach ($methods as $method) {

            $this->addRoute($routesMock, $method);
            $this->addRoute($routesMock, $method, false);
        }
    }

    /**
     * Return config value from config object
     *
     * @param $config
     * @return mixed
     */
    protected function getFromConfig($config)
    {
        return $this->config->get(FormBuilder::CONFIG_NAME . '.' . $config);
    }

    /**
     * Stub the session mock
     */
    protected function stubSession()
    {
        $sessionMock = $this->session;
        $sessionMock->shouldReceive('hasOldInput')->withAnyArgs()->andReturn(false)->byDefault();
        $sessionMock->shouldReceive('hasOldInput')->with('fieldWithOld')->andReturn(true)->byDefault();
        $sessionMock->shouldReceive('getOldInput')->with('fieldWithOld')->andReturn('some-old-value')->byDefault();
    }

    /**
     * Stub the request mock
     */
    protected function stubRequest()
    {
        $requestMock = $this->request;
        $requestMock->shouldReceive('getScheme')->andReturn('http')->byDefault();
        $requestMock->shouldReceive('root')->andReturn('http://test.loc')->byDefault();
    }

    /**
     * Stub the view mock
     */
    protected function stubViewFactory()
    {
        $this->errorBag = new \Illuminate\Support\MessageBag();
        $viewMock = $this->viewFactory;
        $viewMock->shouldReceive('shared')->andReturn($this->errorBag)->byDefault();
    }

    /**
     * Add new error to view
     *
     * @param string $name
     * @param string $message
     */
    protected function setError($name, $message)
    {
        $this->errorBag->add($name, $message);
    }

    /**
     * Reset view (for example after validation errors)
     */
    protected function resetViewFactory()
    {
        $this->stubViewFactory();
    }
}
<?php

require_once('TestController.php');

use A2design\Form\FormBuilder;

class FormBuilderTestBase extends Orchestra\Testbench\TestCase
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

        $configMock = Mockery::mock(\Illuminate\Config\Repository::class);
        $this->stubConfig($configMock);
        $this->config = $configMock;

        $viewMock = Mockery::mock(\Illuminate\Contracts\View\Factory::class);
        $viewMock->shouldReceive("shared")->andReturn([])->byDefault();
        $this->viewFactory = $viewMock;

        $routesMock = Mockery::mock(\Illuminate\Routing\RouteCollection::class);
        $this->createRoutes($routesMock);
        $this->routes = $routesMock;

        $requestMock = Mockery::mock(\Illuminate\Http\Request::class);
        $requestMock->shouldReceive("getScheme")->andReturn('http');
        $requestMock->shouldReceive("root")->andReturn('/');
        $this->request = $requestMock;

        $this->session = Mockery::mock(\Illuminate\Session\Store::class);

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
     * @param $method
     */
    public function addRoute($routesMock, $method, $name = true)
    {
        $controller = new TestController();
        /** @var \Illuminate\Routing\Router $router */
        $router = app()->make(\Illuminate\Routing\Router::class);

        //todo make routes without name

        $url = '/' . $method . '-url';
        $action = 'testController@' . $method;
        $actionPrefix = $this->getFromConfig('route_name_space');
        $fullAction = $actionPrefix . '\\' . $action;
        $name = $method . 'RouteName';

        $route = $router->$method($url, $action);

        if ($name !== false) {
            $route->name($name);
        }

        $routeMock = Mockery::mock($route);
        $routeMock->shouldReceive("getController")->andReturn($controller);

        if ($name !== false) {
            $routesMock->shouldReceive("getByName")->with($name)->andReturn($routeMock)->byDefault();
        }

        $routesMock->shouldReceive("getByAction")->with($fullAction)->andReturn($routeMock)->byDefault();
    }

    /**
     * Set initial config values for the config mock
     *
     * @param \Mockery\MockInterface $configMock
     */
    protected function stubConfig($configMock)
    {
        /** @var \Illuminate\Config\Repository $configs */
        $configs = config();
        $configName = FormBuilder::CONFIG_NAME;

        $configMock->shouldReceive('get')
            ->withAnyArgs()
            ->andReturn(null)
            ->byDefault();

        $configMock->shouldReceive('has')
            ->withAnyArgs()
            ->andReturn(false)
            ->byDefault();

        foreach ($configs->all()[$configName] as $config => $value) {

            $configMock->shouldReceive('get')
                ->with($configName . '.' . $config)
                ->andReturn($value)
                ->byDefault();

            $configMock->shouldReceive('has')
                ->with($configName . '.' . $config)
                ->andReturn(true)
                ->byDefault();
        }
    }

    /**
     * Make routes for all methods
     *
     * @param \Mockery\MockInterface $routesMock
     */
    protected function createRoutes($routesMock)
    {
        $routesMock->shouldReceive("getByName")->withAnyArgs()->andReturnNull()->byDefault();
        $routesMock->shouldReceive("getByAction")->withAnyArgs()->andReturnNull()->byDefault();

        $methods = ['get', 'post', 'patch', 'put', 'delete'];

        foreach ($methods as $method) {

            $this->addRoute($routesMock, $method);
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
}
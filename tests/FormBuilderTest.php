<?php
use A2design\Form\FormBuilder;

class FormBuilderTest extends Orchestra\Testbench\TestCase
{
    /**
     * @var \A2design\Form\FormBuilder
     */
    private $formBuilder;

    /**
     * Mock of \Illuminate\View\Factory
     *
     * @var \Mockery\MockInterface
     */
    private $viewFactory;

    /**
     * Mock of \Illuminate\Session\Store
     *
     * @var \Mockery\MockInterface
     */
    private $session;

    /**
     * Mock of \Illuminate\Config\Repository
     *
     * @var \Mockery\MockInterface
     */
    private $config;

    /**
     * Mock of \Illuminate\Routing\RouteCollection
     *
     * @var \Mockery\MockInterface
     */
    private $routes;

    /**
     * Mock of \Illuminate\Http\Request
     *
     * @var \Mockery\MockInterface
     */
    private $request;

    /**
     * Specify the package service provider
     *
     * @param \Illuminate\Foundation\Application $app
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

        $viewMock = Mockery::mock(\Illuminate\View\Factory::class);
        $viewMock->shouldReceive("shared")->andReturn([])->byDefault();
        $this->viewFactory = $viewMock;

        $routesMock = Mockery::mock(\Illuminate\Routing\RouteCollection::class);
        $routesMock->shouldReceive("getByName")->withAnyArgs()->andReturnNull()->byDefault();
        $routesMock->shouldReceive("getByAction")->withAnyArgs()->andReturnNull()->byDefault();
        $this->routes = $routesMock;

        $configMock = Mockery::mock(\Illuminate\Config\Repository::class);
        $configMock = $this->stubConfig($configMock);
        $this->config = $configMock;

        $this->request = Mockery::mock(\Illuminate\Http\Request::class);
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

    public function testOpeningForm()
    {
        $form1 = $this->formBuilder->create();

        $this->assertContains('<form', $form1, 'Form is not opened');
    }

    /**
     * Set initial config values for the config mock
     *
     * @param \Mockery\MockInterface $configMock
     *
     * @return \Mockery\MockInterface
     */
    private function stubConfig($configMock)
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

        return $configMock;
    }
}
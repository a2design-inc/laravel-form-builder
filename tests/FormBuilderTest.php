<?php
use A2design\Form\FormBuilder;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Session\Store;

use Mockery as m;

class FormBuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var A2design\Form\FormBuilder
     */
    private $formBuilder;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * @var Mockery
     */
    private $viewFactory;

    /**
     * @var Mockery
     */
    private $session;


    /**
     * Setup the test environment initially.
     */
    public function setUp()
    {
        $this->urlGenerator = new UrlGenerator(new RouteCollection(), Request::create('/foo', 'Post'));
        $this->viewFactory = m::mock(Factory::class);
//        $this->viewFactory = app()->make(Factory::class);
        $this->session = m::mock(Store::class);

        // prepare request for test with some data
        $request = Request::create('/foo', 'Post', [
            'Name' => 'Some name'
        ]);

        $request = Request::createFromBase($request);

//        $this->formBuilder = new FormBuilder($this->viewFactory, $this->session, $request);
    }

    public function testTest()
    {
        $this->assertEquals(true, true);
    }
}
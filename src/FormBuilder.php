<?php

namespace A2design\Form;

use Illuminate\Config\Repository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Session\Store;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Routing\RouteUrlGenerator;

/**
 * Class FormBuilder
 * @package A2design\Form
 */
class FormBuilder
{
    /**
     * Config prefix
     */
    const CONFIG_NAME = 'form';

    /**
     * @var Factory
     */
    protected $view;

    /**
     * @var Store
     */
    protected $session;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var RouteCollection
     */
    protected $routes;

    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var \Illuminate\Routing\Route|null
     */
    protected $route = null;

    /**
     * Instance of model for the editing
     *
     * @var Model|null
     */
    protected $entity = null;

    /**
     * Action like as 'controller@method' or route name or just usual url
     *
     * @var string
     */
    protected $action = '';

    /**
     * @var ViewErrorBag
     */
    protected $errors;

    /**
     * Name of entity of the form
     *
     * @var string
     */
    protected $entityName = '';

    /**
     * Name of controller method
     *
     * @var string
     */
    protected $actionMethod = '';

    /**
     * Parameters set for form
     *
     * @var array
     */
    protected $formParameters = [];

    /**
     * Parameters set for input group
     *
     * @var array
     */
    protected $inputGroupParameters = [];

    /**
     * The group is opened or not
     *
     * @var boolean
     */
    protected $inputGroupIsOpened = false;

    /**
     * Saved html for inputs inside a group
     *
     * @var boolean
     */
    protected $inputsWithinGroupHtml = '';

    /**
     * Array of parameter names which can be used for form creating
     *
     * @var array
     */
    protected $formParameterNames = [
        'id',
        'class',
        'method',
        'absolute',
        'url',
        'attrs',
    ];

    /**
     * Array of input types which called like as usual input() method, but with different type parameter
     *
     * @var array
     */
    protected $typeInheritedInputs = [
        'password',
        'text',
        'file',
        //html5
        'color',
        'date',
        'datetime',
        'datetimeLocal',
        'email',
        'number',
        'range',
        'search',
        'tel',
        'time',
        'url',
        'month',
        'week',
    ];

    /**
     * Create a new form builder instance.
     *
     * @param Factory $view
     * @param Store $session
     * @param RouteCollection $routes
     * @param Repository $config
     * @param Request|null $request
     */
    public function __construct(
        Factory $view,
        Store $session,
        RouteCollection $routes,
        Repository $config,
        Request $request = null
    )
    {
        $this->view = $view;
        $this->errors = $view->shared('errors');
        $this->session = $session;
        $this->request = $request;
        $this->routes = $routes;
        $this->config = $config;
    }


    /**
     * Catch calls
     *
     * @param $name
     * @param $arguments
     *
     * @return string
     */
    public function __call($name, $arguments)
    {
        //Call input() method with "type" parameter
        if (in_array($name, $this->typeInheritedInputs)) {

            return $this->callInputType($name, $arguments);
        }
    }

    /**
     * Create a new form
     *
     * @param string $action Action like as 'controller@method' or route name or just usual url
     * @param null|Model $entity
     * @param array $parameters
     *
     * @return string
     */
    public function create($action = '', $entity = null, $parameters = [])
    {
        $this->action = $action;
        $this->entity = $entity;
        $this->route = $this->getRoute($this->action);
        $this->actionMethod = $this->getActionMethod();
        $this->entityName = $this->getEntityName();

        $parameters = $this->setDefaultFromConfig($parameters);
        $parameters = $this->generateComplexFormParameters($parameters);

        $this->formParameters = $parameters;

        return view('form::form-create', compact('parameters'))->render();
    }

    /**
     * Close the form
     *
     * @return string
     */
    public function end()
    {
        return view('form::form-end')->render();
    }

    public function postLink($action = '', $text = '', $entity = null, $parameters = [])
    {
        $this->entity = $entity;
        $this->action = $action;
        $this->route = $this->getRoute($this->action);

        if (!isset( $parameters['id'])) {
            $parameters['id'] = md5(time() . mt_rand());
        }

        $parameters['text'] = $text;
        $parameters['method'] = $this->getRouteMethod($parameters);
        $parameters['form-action'] = $this->getFormAction($parameters);
        $parameters['form-method'] = $this->getFormMethod($parameters);
        $parameters['hidden-inputs'] = $this->getHiddenInputs($parameters);
        $parameters['message'] = !empty($parameters['message']) ? $parameters['message'] : 'Are you sure?';
        $parameters['escaped'] = isset($parameters['escaped']) ? $parameters['escaped'] : true;

        return view('form::post-link', compact('parameters'))->render();
    }

    /**
     * Create new input
     *
     * @param string $name
     * @param string $label
     * @param array $parameters
     * @param string $view
     *
     * @return string
     */
    public function input($name, $label = '', $parameters = [], $view = 'form::input')
    {
        $parameters = $this->setFromInputGroup($parameters);
        $parameters = $this->setFromForm($parameters);
        $parameters = $this->setDefaultFromConfig($parameters);
        $parameters = $this->generateComplexInputParameters($name, $parameters);
        $errors = $this->errors;

        $result = view($view, compact('name', 'label', 'parameters', 'errors'))->render();

        if ($this->inputGroupIsOpened) {
            $this->inputsWithinGroupHtml .= $result;
            return null;
        }

        return $result;
    }

    /**
     * Create new checkbox
     *
     * @param string $name
     * @param string $label
     * @param array $parameters
     *
     * @return string
     */
    public function checkbox($name, $label = '', $parameters = [])
    {
        //because the bootstrap class is incompatible with checkboxes
        $parameters['form-control-class'] = '';

        //by default the checkbox used "checkbox-label" which wrap the checkbox, not usual input label
        if (!isset($parameters['label'])) {
            $parameters['label'] = false;
        }

        $parameters = $this->setFromInputGroup($parameters);

        $parameters = $this->setFromForm($parameters);
        $parameters = $this->setDefaultFromConfig($parameters);
        $parameters = $this->generateComplexCheckboxParameters($name, $parameters);

        return $this->input($name, $label, $parameters, 'form::checkbox');
    }

    /**
     * Create new textarea
     *
     * @param string $name
     * @param string $label
     * @param array $parameters
     *
     * @return string
     */
    public function textarea($name, $label = '', $parameters = [])
    {
        return $this->input($name, $label, $parameters, 'form::textarea');
    }

    /**
     * Shortcut for textarea
     *
     * @param string $name
     * @param string $label
     * @param array $parameters
     *
     * @return string
     */
    public function text($name, $label = '', $parameters = [])
    {
        return $this->textarea($name, $label, $parameters);
    }

    /**
     * Create new input
     *
     * @param $name
     * @param array $parameters
     *
     * @return string
     */
    public function hidden($name, $parameters = [])
    {
        $parameters['only-input'] = true;
        $parameters['type'] = 'hidden';

        return $this->input($name, '', $parameters);
    }

    /**
     * Create new button
     *
     * @param string $text
     * @param array $parameters
     * @param string $view
     *
     * @return string
     */
    public function button($text = 'Submit', $parameters = [], $view = 'form::button')
    {
        $parameters['text'] = $text;
        $parameters = $this->setFromForm($parameters);
        $parameters = $this->setDefaultFromConfig($parameters);
        $parameters = $this->generateComplexButtonParameters($parameters);

        //define variable for input layout using
        $name = isset($parameters['name']) ? $parameters['name'] : '';
        $label = isset($parameters['label-text']) ? $parameters['label-text'] : '';

        return $this->input($name, $label, $parameters, $view);
    }

    /**
     * Create new link similar to button
     *
     * @param string $text
     * @param string $link
     * @param array $parameters
     *
     * @return string
     */
    public function buttonLink($text = 'Cancel', $link = '/', $parameters = [])
    {
        $parameters['href'] = $link;
        $parameters['type'] = kebab_case($text);

        return $this->button($text, $parameters, 'form::button-link');
    }

    /**
     * Create new button (alias)
     *
     * @param string $text
     * @param array $parameters
     *
     * @return string
     */
    public function submit($text = 'Submit', $parameters = [])
    {
        return $this->button($text, $parameters);
    }

    /**
     * Create new button (type reset)
     *
     * @param string $text
     * @param array $parameters
     *
     * @return string
     */
    public function reset($text = 'Reset', $parameters = [])
    {
        if (!isset($parameters['type'])) {
            $parameters['type'] = 'reset';
        }

        return $this->button($text, $parameters);
    }

    /**
     * Open group with inputs
     *
     * This method prevent output
     * The html is returned in inputGroupEnd with stashed inputs inside overall wrapper
     *
     * @param array $parameters
     *
     * @return string
     */
    public function inputGroup($parameters = [])
    {
        $this->inputGroupIsOpened = true;

        $parameters = $this->setFromForm($parameters);
        $parameters = $this->setDefaultFromConfig($parameters);
        $parameters = $this->generateComplexInputGroupParameters($parameters);

        //because input group equal to input with several buttons inside one input html wrap
        $parameters = $this->generateComplexInputParameters('', $parameters);

        $this->inputGroupParameters = $parameters;

        return '';
    }

    /**
     * Open group with buttons, similar to inputGroup
     *
     * @param array $parameters
     *
     * @return string
     */
    public function buttonGroup($parameters = [])
    {
        $this->inputGroupIsOpened = true;

        $parameters = $this->setFromForm($parameters);
        $parameters = $this->setDefaultFromConfig($parameters);
        //because button group equal to button with several buttons inside one input html wrap
        $parameters = $this->generateComplexButtonGroupParameters($parameters);

        return $this->inputGroup($parameters);
    }

    /**
     * Close the group and return elements stashed inside the group
     *
     * @return string
     */
    public function buttonGroupEnd()
    {
        return $this->inputGroupEnd();
    }

    /**
     * Close the group and return elements stashed inside the group
     *
     * @return string
     */
    public function inputGroupEnd()
    {
        $this->inputGroupIsOpened = false;
        $parameters = $this->inputGroupParameters;
        $this->inputGroupParameters = [];
        $html = $this->inputsWithinGroupHtml;
        $this->inputsWithinGroupHtml = '';
        $parameters['only-input'] = false;
        $errors = $this->errors;

        $name = isset($parameters['name']) ? $parameters['name'] : '';
        $label = isset($parameters['label-text']) ? $parameters['label-text'] : '';

        return view('form::group', compact('html', 'name', 'parameters', 'label', 'errors'))->render();
    }

    /**
     * Create new select
     *
     * @param string $name
     * @param string $label
     * @param array $parameters
     *
     * @return string
     */
    public function select($name, $label = '', $parameters = [])
    {
        $parameters['value'] = $this->getSelectValue($parameters, $name);

        if (empty($parameters['options'])) {
            $parameters['options'] = [];
        }

        return $this->input($name, $label, $parameters, 'form::select');
    }

    /**
     * Create new radio
     *
     * @param string $name
     * @param string $label
     * @param array $parameters
     *
     * @return string
     */
    public function radio($name, $label = '', $parameters = [])
    {
        //because the bootstrap class is incompatible with radio
        $parameters['form-control-class'] = '';

        if (empty($parameters['options'])) {
            $parameters['options'] = [];
        }

        $parameters = $this->setFromForm($parameters);
        $parameters = $this->setDefaultFromConfig($parameters);
        $parameters = $this->generateComplexRadioParameters($name, $parameters);

        return $this->input($name, $label, $parameters, 'form::radio');
    }

    /**
     * Return method name of controller by the route
     *
     * @param array$parameters
     *
     * @return string
     */
    protected function getRouteMethod($parameters)
    {
        if (isset($parameters['method'])) {
            return $parameters['method'];
        }

        if (empty($this->route)) {
            return '';
        }

        if (!is_array($this->route->methods()) && !isset($this->route->methods()[0])) {
            return '';
        }

        return $this->route->methods()[0];
    }

    /**
     * Return route instance by action name
     *
     * @param string $routeName Route name or action
     *
     * @return \Illuminate\Routing\Route|null
     */
    protected function getRoute($routeName)
    {
        /** @var RouteCollection $routes */
        $route = $this->routes->getByName($routeName);

        if (!empty($route)) {
            return $route;
        }

        // Route name space can't be used dynamically because it is on protected scope (L5.4)
        // Therefore defined in config file :(
        return $this->routes->getByAction($this->getConfig('route_name_space') . '\\' . $routeName);
    }

    /**
     * Get value for input by name
     *
     * @param string $name
     * @param array $parameters
     *
     * @return string
     */
    protected function getInputValue($name, $parameters)
    {
        if (isset($parameters['value'])) {
            return $parameters['value'];
        }

        if ($this->session->hasOldInput($name)) {
            return $this->session->getOldInput($name);
        }

        if (!empty($this->entity)) {
            return $this->entity->$name;
        }

        return '';
    }

    /**
     * Generate id for form
     *
     * @param array $parameters
     * @return string
     */
    protected function getFormId($parameters)
    {
        if (isset($parameters['id'])) {
            return $parameters['id'];
        }

        if (!$this->getConfig('generate_id')) {
            return '';
        }

        $id = '';

        if (!empty($this->actionMethod)) {
            $id = kebab_case($this->actionMethod);
        }

        if (!empty($this->entityName)) {
            $id = $id . '-' . kebab_case($this->entityName);
        }

        return $id;
    }

    /**
     * Generate ind for input by name
     *
     * @param string $name
     * @param array $parameters
     *
     * @return string
     */
    protected function getInputId($name, $parameters)
    {
        if (isset($parameters['id'])) {
            return $parameters['id'];
        }

        if (!$this->getConfig('generate_id')) {
            return '';
        }

        $id = kebab_case($name);

        if (!empty($this->entityName)) {
            $id = kebab_case($this->entityName) . '-' . $id;
        }

        if (!empty($this->route)) {
            $id = kebab_case($this->getActionMethod()) . '-' . $id;
        }

        return $id;
    }

    /**
     * Return name of entity of the form
     *
     * @return string
     */
    protected function getEntityName()
    {
        if (!empty($this->entity)) {
            return (new \ReflectionClass($this->entity))->getShortName();
        }

        if (!empty($this->route)) {
            $controllerName = (new \ReflectionClass($this->route->getController()))->getShortName();

            $namePos = strpos($controllerName, $this->getConfig('controller_naming'));

            if ($namePos === false) {
                return '';
            }

            return substr($controllerName, 0, $namePos);
        }

        return '';
    }

    /**
     * Set parameter from config if it is not set
     * Parameter key in on kebab-case, config key is on snake_case
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function setDefaultFromConfig($parameters)
    {
        $configurableParameters = [
            'control-label-class',
            'label-grid-class',
            'input-grid-class',
            'form-group-class',
            'form-control-class',
            'error-form-group-class',
            'error-class',
            'offset-input-grid-class',
            'btn-class',
            'form-direction-class',
            'use-grid',
            'bootstrap',
            'wrapper',
            'form-group-wrapper',
            'label-after',
        ];

        foreach ($configurableParameters as $configurableParameter) {

            if (isset($parameters[$configurableParameter])) {
                continue;
            }

            $parameters[$configurableParameter] = $this->getConfig(snake_case(camel_case($configurableParameter)));
        }

        return $parameters;
    }

    /**
     * Set parameter from form parameters if it is not set
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function setFromForm($parameters)
    {
        // use global parameters for the form instance
        foreach ($this->formParameters as $name => $formParameter) {

            // skip common parameters which used for all elements
            // e.g. if 'id' set for form is set only for the form
            if (in_array($name, $this->formParameterNames)) {
                continue;
            }

            if (!isset($parameters[$name])) {
                $parameters[$name] = $formParameter;
            }
        }

        return $parameters;
    }

    /**
     * Return package config value by name
     *
     * @param $name
     *
     * @return mixed
     */
    protected function getConfig($name)
    {
        return $this->config->get(self::CONFIG_NAME . '.' . $name);
    }

    /**
     * Call input() method with "type" parameter
     *
     * @param $type
     * @param $arguments
     *
     * @return string
     */
    protected function callInputType($type, $arguments)
    {
        if (!isset($arguments[0])) {
            $arguments[0] = '';
        }

        if (!isset($arguments[1])) {
            $arguments[1] = '';
        }

        if (!isset($arguments[2])) {
            $arguments[2] = [];
        }

        $arguments[2]['type'] = kebab_case($type);

        return $this->input(...$arguments);
    }

    /**
     * Return string with classes for label
     *
     * @param array $parameters
     *
     * @return string
     */
    protected function getLabelClasses($parameters)
    {
        $classes = [];

        if (isset($parameters['label-class']) && $parameters['label-class']) {
            $classes[] = $parameters['label-class'];
        }

        if (
            isset($parameters['use-grid']) && $parameters['use-grid']
            && isset($parameters['bootstrap']) && $parameters['bootstrap']
            && isset($parameters['label-grid-class']) && $parameters['label-grid-class']
        ) {
            $classes[] = $parameters['label-grid-class'];
        }

        if (
            isset($parameters['bootstrap']) && $parameters['bootstrap']
            && isset($parameters['control-label-class']) && $parameters['control-label-class']
        ) {
            $classes[] = $parameters['control-label-class'];
        }

        return implode(' ', $classes);
    }

    /**
     * Return string with classes for the wrapper
     *
     * @param array $parameters
     * @param string $name
     *
     * @return string
     */
    protected function getFormGroupClasses($parameters, $name = '')
    {
        $classes = [];

        if (
            isset($parameters['bootstrap']) && $parameters['bootstrap']
            && isset($parameters['form-group-class']) && $parameters['form-group-class']
        ) {
            $classes[] = $parameters['form-group-class'];
        }

        if ($this->inputHasError($parameters, $name) && $parameters['error-form-group-class']) {
            $classes[] = $parameters['error-form-group-class'];
        }

        if (isset($parameters['form-group-wrapper-class']) && $parameters['form-group-wrapper-class']) {
            $classes[] = $parameters['form-group-wrapper-class'];
        }

        if (isset($parameters['required']) && $parameters['required'] === true) {
            $classes[] = 'required';
        }

        return implode(' ', $classes);
    }

    /**
     * Return string with classes for the wrapper
     *
     * @param array $parameters
     *
     * @return string
     */
    protected function getInputWrapperClasses($parameters)
    {
        $classes = [];

        if (
            isset($parameters['bootstrap']) && $parameters['bootstrap']
            && isset($parameters['use-grid']) && $parameters['use-grid']
        ) {
            if (empty($parameters['label'])) {
                $classes[] = $parameters['offset-input-grid-class'];
            } else {
                $classes[] = $parameters['input-grid-class'];
            }
        }

        if (isset($parameters['wrapper-class']) && $parameters['wrapper-class']) {
            $classes[] = $parameters['wrapper-class'];
        }

        return implode(' ', $classes);
    }

    /**
     * Return string with classes for the input
     *
     * @param array $parameters
     * @param string $name
     *
     * @return string
     */
    protected function getInputClasses($parameters, $name)
    {
        $classes = [];

        if (
            isset($parameters['bootstrap']) && $parameters['bootstrap']
            && isset($parameters['form-control-class']) && $parameters['form-control-class']
        ) {
            $classes[] = $parameters['form-control-class'];
        }

        if (isset($parameters['class']) && $parameters['class']) {
            $classes[] = $parameters['class'];
        }

        if ($this->inputHasError($parameters, $name) && $parameters['error-class']) {
            $classes[] = $parameters['error-class'];
        }

        return implode(' ', $classes);
    }

    /**
     * Return string with classes for the form
     *
     * @param array $parameters
     *
     * @return string
     */
    protected function getFormClasses($parameters)
    {
        $classes = [];

        if (
            isset($parameters['bootstrap']) && $parameters['bootstrap']
            && isset($parameters['use-grid']) && $parameters['use-grid']
            && isset($parameters['form-direction-class']) && $parameters['form-direction-class'])
        {
            $classes[] = $parameters['form-direction-class'];
        }

        if (isset($parameters['class']) && $parameters['class']) {
            $classes[] = $parameters['class'];
        }

        return implode(' ', $classes);
    }

    /**
     * Return true if input with the name has any validation error
     *
     * @param array $parameters
     * @param string $name
     *
     * @return bool
     */
    protected function inputHasError($parameters, $name)
    {
        if (isset($parameters['error']) && $parameters['error'] === false) {
            return false;
        }

        return $this->errors->has($name)
            || isset($parameters['error']) && $parameters['error'];
    }


    /**
     * Return string with classes for the wrapper
     *
     * @param array $parameters
     *
     * @return string
     */
    protected function getButtonWrapperClasses($parameters)
    {
        return $this->getInputWrapperClasses($parameters);
    }


    /**
     * Return string with classes for the button
     *
     * @param array $parameters
     *
     * @return string
     */
    protected function getButtonClasses($parameters)
    {
        $classes = [];

        if (
            isset($parameters['bootstrap']) && $parameters['bootstrap']
            && isset($parameters['btn-class']) && $parameters['btn-class']
        ) {
            $classes[] = $parameters['btn-class'];
        }

        if (isset($parameters['class']) && $parameters['class']) {
            $classes[] = $parameters['class'];
        }

        return implode(' ', $classes);
    }

    /**
     * Return name of controller method or empty string
     *
     * @return string
     */
    protected function getActionMethod()
    {
        if (empty($this->route)) {
            return '';
        }

        return $this->route->getActionMethod();
    }

    /**
     * Return url for form action attribute
     *
     * @param array $parameters
     *
     * @return string
     */
    protected function getFormAction($parameters)
    {
        if (isset($parameters['url'])) {
            return $parameters['url'];
        }

        $absolute = false;

        if (!empty($parameters['absolute'])) {
            $absolute = $parameters['absolute'];
        }

        $urlGenerator = new UrlGenerator($this->routes, $this->request);
        $routeUrlGenerator = new RouteUrlGenerator($urlGenerator, $this->request);

        $urlParams = [];

        if (!empty($this->entity)) {
            $urlParams['id'] = $this->entity->getKey();
        }

        if (!empty($this->route)) {
            return $routeUrlGenerator->to($this->route, $urlParams, $absolute);
        }

        return $this->action;
    }

    /**
     * Return string for form method attribute
     *
     * @param array $parameters
     *
     * @return string
     */
    protected function getFormMethod($parameters)
    {
        if (!isset($parameters['method'])) {
            return 'post';
        }

        if (strtolower($parameters['method']) === 'get') {
            return strtolower($parameters['method']);
        }

        return 'post';
    }

    /**
     * Return string with laravel-required hidden inputs
     *
     * @param array $parameters
     *
     * @return string
     */
    protected function getHiddenInputs($parameters)
    {
        $result = '';

        if (!isset($parameters['method']) || !in_array($parameters['method'], ['get','GET'])) {
            $result .= csrf_field() . ' ';
        }

        if (!empty($parameters['method']) && !in_array($parameters['method'], ['get','GET', 'post', 'POST'])) {
            $result .= method_field(strtoupper($parameters['method'])) . ' ';
        }

        return $result;
    }

    /**
     * Fill parameters based on other parameters
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function generateComplexFormParameters($parameters)
    {
        $parameters['method'] = $this->getRouteMethod($parameters);
        $parameters['form-action'] = $this->getFormAction($parameters);
        $parameters['form-method'] = $this->getFormMethod($parameters);
        $parameters['hidden-inputs'] = $this->getHiddenInputs($parameters);
        $parameters['id'] = $this->getFormId($parameters);
        $parameters['form-classes'] = $this->getFormClasses($parameters);

        if (!isset($parameters['enctype']) && (!empty($parameters['has-files']) || !empty($parameters['file']))) {
            $parameters['enctype'] = 'multipart/form-data';
        }

        return $parameters;
    }

    /**
     * Fill parameters based on other parameters
     *
     * @param string $name
     * @param array $parameters
     *
     * @return array
     */
    protected function generateComplexInputParameters($name, $parameters)
    {
        if (!isset($parameters['label'])) {
            $parameters['label'] = true;
        }

        if (isset($parameters['type']) && $parameters['type'] === 'file') {
            $parameters['form-control-class'] = false;
        }

        $parameters['value'] = $this->getInputValue($name, $parameters);
        $parameters['id'] = $this->getInputId($name, $parameters);
        $parameters['label-classes'] = $this->getLabelClasses($parameters);
        $parameters['form-group-wrapper-classes'] = $this->getFormGroupClasses($parameters, $name);
        $parameters['input-wrapper-classes'] = $this->getInputWrapperClasses($parameters);
        $parameters['input-classes'] = $this->getInputClasses($parameters, $name);

        if (!isset($parameters['only-input'])) {
            $parameters['only-input'] = $this->inputGroupIsOpened;
        }

        if (!isset($parameters['label-escaped'])) {
            $parameters['label-escaped'] = true;
        }

        if (!isset($parameters['escaped'])) {
            $parameters['escaped'] = true;
        }

        return $parameters;
    }

    /**
     * Fill parameters based on other parameters
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function generateComplexInputGroupParameters($parameters)
    {
        if (empty($parameters['label-text'])) {
            $parameters['label-after'] = false;
        }

        if (empty($parameters['label-text']) && !isset($parameters['label'])) {
            $parameters['label'] = false;
        }

        if (!isset($parameters['id'])) {
            $parameters['id'] = '';
        }

        return $parameters;
    }

    /**
     * Fill parameters based on other parameters
     *
     * @param string $name
     * @param array $parameters
     *
     * @return array
     */
    protected function generateComplexCheckboxParameters($name, $parameters)
    {
        if ($parameters['label'] !== false && !isset($parameters['checkbox-label'])) {
            $parameters['checkbox-label'] = false;
        }

        if ($parameters['bootstrap'] && !$parameters['label']) {
            $parameters['form-group-class'] .= ' checkbox';
        }

        if ($this->getInputValue($name, $parameters) == true) {
            $parameters['checked'] = true;
        }

        return $parameters;
    }

    /**
     * Fill parameters based on other parameters
     *
     * @param string $name
     * @param array $parameters
     *
     * @return array
     */
    protected function generateComplexRadioParameters($name, $parameters)
    {
        if (!isset($parameters['label'])) {
            $parameters['label'] = true;
        }

        if (!isset($parameters['inline'])) {
            $parameters['inline'] = false;
        }

        if (!isset($parameters['radio-label-class'])) {
            $parameters['radio-label-class'] = '';
        }

        if ($parameters['bootstrap'] && $parameters['inline']) {
            $parameters['radio-label-class'] .= ' radio-inline';
        }

        return $parameters;
    }

    /**
     * Return label for button
     *
     * @param array $parameters
     *
     * @return boolean|string
     */
    protected function getButtonLabel($parameters)
    {
        if (!empty($parameters['label-text'])) {
            return true;
        }

        // without label by default
        if (!isset($parameters['label'])) {
            return false;
        }

        return $parameters['label'];
    }

    /**
     * Return type for button
     *
     * @param array $parameters
     *
     * @return string
     */
    protected function getButtonType($parameters)
    {
        // by default
        if (!isset($parameters['type'])) {
            return 'submit';
        }

        return $parameters['type'];
    }

    /**
     * Return is escaped or not
     *
     * @param array $parameters
     *
     * @return boolean
     */
    protected function getButtonEscaping($parameters)
    {
        // by default
        if (!isset($parameters['escaped'])) {
            return true;
        }

        return $parameters['escaped'];
    }

    /**
     * Fill parameters based on other parameters
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function generateComplexButtonParameters($parameters)
    {
        $parameters['label'] = $this->getButtonLabel($parameters);

        if (!empty($parameters['label'])) {
            $parameters['label-classes'] = $this->getLabelClasses($parameters);
        }

        $parameters['type'] = $this->getButtonType($parameters);
        $parameters['escaped'] = $this->getButtonEscaping($parameters);
        //use type instead input name for button id
        $parameters['id'] = $this->getInputId($parameters['type'], $parameters);
        $parameters['form-group-wrapper-classes'] = $this->getFormGroupClasses($parameters);
        $parameters['input-wrapper-classes'] = $this->getButtonWrapperClasses($parameters);
        $parameters['button-classes'] = $this->getButtonClasses($parameters);

        return $parameters;
    }

    /**
     * Fill parameters based on other parameters
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function generateComplexButtonGroupParameters($parameters)
    {
        if (!empty($parameters['label'])) {
            $parameters['label-classes'] = $this->getLabelClasses($parameters);
        }

        $parameters['input-wrapper-classes'] = $this->getButtonWrapperClasses($parameters);
        $parameters['button-classes'] = $this->getButtonClasses($parameters);

        return $parameters;
    }

    /**
     * Fill parameters from the group
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function setFromInputGroup($parameters)
    {
        if (!$this->inputGroupIsOpened) {
            return $parameters;
        }

        return $this->addOnlyNewParameters($parameters, $this->inputGroupParameters);
    }

    /**
     * Add parameters which have not set yet
     *
     * @param array $parameters
     * @param array $newParameters
     *
     * @return array
     */
    protected function addOnlyNewParameters($parameters, $newParameters)
    {
        foreach ($newParameters as $name => $parameter) {
            if (isset($parameters[$name])) {
                continue;
            }
            $parameters[$name] = $parameter;
        }

        return $parameters;
    }

    /**
     * Return value for the select
     *
     * Moved to different method because unlike usual input
     * the select use "old" values despite the "value" parameter
     *
     * @param array $parameters
     * @param string $name
     *
     * @return array|string
     */
    protected function getSelectValue($parameters, $name)
    {
        $customValue = '';

        if (!empty($this->entity)) {
            $customValue = $this->entity->$name;
        }

        if (isset($parameters['value'])) {
            $customValue = $parameters['value'];
        }

        if (isset($parameters['use-old']) && $parameters['use-old'] === false) {
            return $customValue;
        }

        if ($this->session->hasOldInput($name)) {
            return $this->session->getOldInput($name);
        }

        return $customValue;
    }
}
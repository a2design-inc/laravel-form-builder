<?php

namespace A2design\Form;

use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\ViewErrorBag;

/**
 * Class FormBuilder
 * @package A2design\Form
 *
 * TODO button-link
 * TODO button-link-cancel
 *
 * TODO? submit
 * TODO checkbox
 * TODO radio
 * TODO file
 * TODO image
 * TODO hidden
 * TODO reset
 * TODO textarea
 *
 * TODO tests
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
     * Action for form 'controller@method'
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
     * Parameters set for button group
     *
     * @var array
     */
    protected $buttonGroupParameters = [];

    /**
     * The group is opened or not
     *
     * @var boolean
     */
    protected $buttonGroupIsOpened = false;

    /**
     * Saved html for buttons inside a group
     *
     * @var boolean
     */
    protected $buttonsWithinGroupHtml = '';

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
    ];

    /**
     * Array of input types which called like as usual input() method, but with different type parameter
     *
     * @var array
     */
    protected $typeInheritedInputs = [
        'password',
        'text',
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
     * @param Request|null $request
     */
    public function __construct(Factory $view, Store $session, Request $request = null)
    {
        $this->view = $view;
        $this->errors = $view->shared('errors');
        $this->session = $session;
        $this->request = $request;
    }


    /**
     * Catch calls
     *
     * @param $name
     * @param $arguments
     *
     * @return Factory|\Illuminate\View\View
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
     * @param string $action
     * @param null|Model $entity
     * @param array $parameters
     *
     * @return Factory|\Illuminate\View\View
     */
    public function create($action = '', $entity = null, $parameters = [])
    {
        $this->entity = $entity;
        $this->action = $action;
        $this->route = $this->getRouteByAction($action);
        $this->actionMethod = $this->getActionMethod();
        $this->entityName = $this->getEntityName();

        $parameters = $this->setDefaultFromConfig($parameters);
        $parameters = $this->generateComplexFormParameters($parameters);

        $this->formParameters = $parameters;

        return view('form::form-create', compact('parameters'));
    }

    /**
     * Close the form
     *
     * @return \Illuminate\View\View
     */
    public function end()
    {
        return view('form::form-end');
    }

    /**
     * Create new input
     *
     * @param $name
     * @param string $label
     * @param array $parameters
     *
     * @return Factory|\Illuminate\View\View
     */
    public function input($name, $label = '', $parameters = [])
    {
        $parameters = $this->setFromForm($parameters);
        $parameters = $this->setDefaultFromConfig($parameters);
        $parameters = $this->generateComplexInputParameters($name, $parameters);

        return view('form::input', compact('name', 'label', 'parameters'));
    }

    /**
     * Create new button
     *
     * @param string $text
     * @param array $parameters
     *
     * @return Factory|\Illuminate\View\View|null
     */
    public function button($text = 'Submit', $parameters = [])
    {
        $parameters = $this->setFromForm($parameters);
        $parameters = $this->setDefaultFromConfig($parameters);
        $parameters = $this->generateComplexButtonParameters($parameters);

        //define variable for input layout using
        $name = isset($parameters['name']) ? $parameters['name'] : '';
        $label = isset($parameters['label-text']) ? $parameters['label-text'] : '';

        $onlyInput = $this->buttonGroupIsOpened;

        $result = view('form::button', compact('text', 'parameters', 'name', 'onlyInput', 'label'));

        if ($onlyInput) {
            $this->buttonsWithinGroupHtml .= $result;
            return null;
        }

        return $result;
    }

    /**
     * Create new button (alias)
     *
     * @param string $text
     * @param array $parameters
     *
     * @return Factory|\Illuminate\View\View|null
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
     * @return Factory|\Illuminate\View\View|null
     */
    public function reset($text = 'Reset', $parameters = [])
    {
        if (!isset($parameters['type'])) {
            $parameters['type'] = 'reset';
        }

        return $this->button($text, $parameters);
    }

    public function buttonGroup($parameters = [])
    {
        $parameters = $this->setFromForm($parameters);
        $parameters = $this->setDefaultFromConfig($parameters);
        //because button group equal to button with several buttons inside one input html wrap
        $parameters = $this->generateComplexButtonParameters($parameters);
        $this->buttonGroupParameters = $parameters;

        $this->buttonGroupIsOpened = true;
    }

    public function buttonGroupEnd()
    {
        $this->buttonGroupIsOpened = false;
        $parameters = $this->buttonGroupParameters;
        $this->buttonGroupParameters = [];
        $buttons = $this->buttonsWithinGroupHtml;
        $this->buttonsWithinGroupHtml = '';

        $name = isset($parameters['name']) ? $parameters['name'] : '';
        $label = isset($parameters['label-text']) ? $parameters['label-text'] : '';

        return view('form::button-group', compact('buttons', 'name', 'parameters', 'label'));
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
     * @param string $action
     *
     * @return \Illuminate\Routing\Route|null
     */
    protected function getRouteByAction($action)
    {
        // Route name space can't be used dynamically because it is on protected scope (L5.4)
        // Therefore defined in config file :(
        return \Route::getRoutes()->getByAction($this->getConfig('route_name_space') . '\\' . $action);
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

        $key = $this->transformKey($name);

        $oldValue = $this->session->getOldInput($key);

        if ($oldValue !== null) {
            return $oldValue;
        }

        if (!empty($this->entity)) {
            return $this->entity->$name;
        }

        return '';
    }

    /**
     * Transform key from array to dot syntax.
     *
     * @param  string $key
     *
     * @return mixed
     */
    protected function transformKey($key)
    {
        return str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $key);
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
            'button-grid-class',
            'btn-class',
            'form-direction-class',
            'use-grid',
            'bootstrap',
            'wrapper',
            'form-group-wrapper',
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
        return config(self::CONFIG_NAME . '.' . $name);
    }

    /**
     * Call input() method with "type" parameter
     *
     * @param $type
     * @param $arguments
     *
     * @return Factory|\Illuminate\View\View
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
            && isset($parameters['input-grid-class']) && $parameters['input-grid-class']
        ) {
            $classes[] = $parameters['input-grid-class'];
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
        $classes = [];

        if (
            isset($parameters['bootstrap']) && $parameters['bootstrap']
            && isset($parameters['use-grid']) && $parameters['use-grid']
            && isset($parameters['button-grid-class']) && $parameters['button-grid-class']
        ) {
            if (empty($parameters['label'])) {
                $classes[] = $parameters['button-grid-class'];
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
        $absolute = false;

        if (isset($parameters['absolute'])) {
            $absolute = $parameters['absolute'];
        }

        if (isset($parameters['url'])) {
            return $parameters['url'];
        }

        if (!empty($this->action) && !empty($this->entity)) {
            return action($this->action, ['id' => $this->entity->id], $absolute);
        }

        if (!empty($this->action)) {
            return action($this->action, [], $absolute);
        }
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

        if ($parameters['method'] === 'get' || $parameters['method'] === 'GET') {
            return $parameters['method'];
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

        if (isset($parameters['method']) && !in_array($parameters['method'], ['get','GET', 'post', 'POST'])) {
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
        $parameters['value'] = $this->getInputValue($name, $parameters);
        $parameters['id'] = $this->getInputId($name, $parameters);
        $parameters['label-classes'] = $this->getLabelClasses($parameters);
        $parameters['form-group-wrapper-classes'] = $this->getFormGroupClasses($parameters, $name);
        $parameters['input-wrapper-classes'] = $this->getInputWrapperClasses($parameters);
        $parameters['input-classes'] = $this->getInputClasses($parameters, $name);

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
}
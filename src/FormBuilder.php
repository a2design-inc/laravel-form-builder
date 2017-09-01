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
 * TODO single setting to disable grids/bootstrap
 *
 * TODO button
 * TODO submit
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
     * Array of parameter names which can be used for several elements
     * The parameters which are not unique for the element
     * For example, 'id' can be used for form, input etc
     *
     * @var array
     */
    protected $commonParameters = [
        'id',
        'class',
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
        $this->route = $this->getRouteByAction($action);
        $this->actionMethod = $this->route->getActionMethod();
        $this->entity = $entity;
        $this->entityName = $this->getEntityName();
        $this->formParameters = $parameters;

        if (!isset($parameters['method']) && !empty($this->getRouteMethod())) {
            $parameters['method'] = $this->getRouteMethod();
        }

        if (!isset($parameters['id']) && $this->getConfig('generate_id') && !empty($this->getFormId())) {
            $parameters['id'] = $this->getFormId();
        }

        return view('form::form-create', compact('action', 'parameters', 'entity'));
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
        $entity = $this->entity;

        $parameters = $this->setDefaultFromConfig($parameters);
        $parameters = $this->setFromForm($parameters);

        $value = $this->getInputValue($name);

        if (isset($parameters['value'])) {
            $value = $parameters['value'];
        }

        if (!isset($parameters['id']) && $this->getConfig('generate_id') && !empty($this->getInputId($name))) {
            $parameters['id'] = $this->getInputId($name);
        }

        $parameters['label-classes'] = $this->getLabelClasses($parameters);
        $parameters['form-group-wrapper-classes'] = $this->getFormGroupClasses($parameters, $name);
        $parameters['input-wrapper-classes'] = $this->getInputWrapperClasses($parameters);
        $parameters['input-classes'] = $this->getInputClasses($parameters, $name);

        return view('form::input', compact('name', 'label', 'parameters', 'entity', 'value'));
    }

    /**
     * Return method name of controller by the route
     *
     * @return string
     */
    protected function getRouteMethod()
    {
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
     *
     * @return string
     */
    protected function getInputValue($name)
    {
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
     * @return string
     */
    protected function getFormId()
    {
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
     * @return string
     */
    protected function getInputId($name)
    {
        $id = kebab_case($name);

        if (!empty($this->entityName)) {
            $id = kebab_case($this->entityName) . '-' . $id;
        }

        if (!empty($this->route)) {
            $id = kebab_case($this->route->getActionMethod()) . '-' . $id;
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
            if (in_array($name, $this->commonParameters)) {
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

        if (isset($parameters['label-class']) && $parameters['label-class'] !== false) {
            $classes[] = $parameters['label-class'];
        }

        if (isset($parameters['label-grid-class']) && $parameters['label-grid-class'] !== false) {
            $classes[] = $parameters['label-grid-class'];
        }

        if (isset($parameters['control-label-class']) && $parameters['control-label-class'] !== false) {
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
    protected function getFormGroupClasses($parameters, $name)
    {
        $classes = [];

         if (isset($parameters['form-group-class']) && $parameters['form-group-class'] !== false) {
             $classes[] = $parameters['form-group-class'];
         }

        if ($this->inputHasError($parameters, $name) && $parameters['error-form-group-class'] !== false) {
            $classes[] = $parameters['error-form-group-class'];
        }

         if (isset($parameters['form-group-wrapper-class']) && $parameters['form-group-wrapper-class'] !== false) {
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

        if (isset($parameters['input-grid-class']) && $parameters['input-grid-class'] !== false) {
            $classes[] = $parameters['input-grid-class'];
        }

        if (isset($parameters['wrapper-class']) && $parameters['wrapper-class'] !== false) {
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

        if (isset($parameters['form-control-class']) && $parameters['form-control-class'] !== false) {
            $classes[] = $parameters['form-control-class'];
        }

        if (isset($parameters['class']) && $parameters['class'] !== false) {
            $classes[] = $parameters['class'];
        }

        if ($this->inputHasError($parameters, $name) && $parameters['error-class'] !== false) {
            $classes[] = $parameters['error-class'];
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
        return $this->errors->has($name)
            || isset($parameters['error']) && $parameters['error'] !== false;
    }
}
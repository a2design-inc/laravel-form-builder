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
 * TODO? move html classes etc from template to the class and generate one string
 * TODO email, etc methods for input types
 * TODO button
 * TODO write readme about config parameters
 * TODO tests
 * TODO comments
 */
class FormBuilder
{
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
     * @var Model
     */
    protected $entity = null;

    /**
     * @var ViewErrorBag
     */
    protected $errors;

    /**
     * @var string
     */
    protected $entityName = '';

    /**
     * @var string
     */
    protected $actionMethod = '';

    /**
     * @var array
     */
    protected $formParameters = [];

    /**
     * Array of parameter names which can be used for several elements
     * The parameters which are not unique for the element where they can be used
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


    public function __call($name, $arguments)
    {
        if (in_array($name, $this->typeInheritedInputs)) {

            $arguments[2]['type'] = kebab_case($name);

            return $this->input(...$arguments);
        }
    }

    /**
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
     * @return \Illuminate\View\View
     */
    public function end()
    {
        return view('form::form-end');
    }

    /**
     * @param $name
     * @param string $label
     * @param array $parameters
     * @return Factory|\Illuminate\View\View
     */
    public function input($name, $label = '', $parameters = [])
    {
        $entity = $this->entity;
        $value = $this->getInputValue($name);

        if (isset($parameters['value'])) {
            $value = $parameters['value'];
        }

        if (!isset($parameters['id']) && $this->getConfig('generate_id') && !empty($this->getInputId($name))) {
            $parameters['id'] = $this->getInputId($name);
        }

        $parameters = $this->setDefaultFromConfig($parameters);
        $parameters = $this->setFromForm($parameters);

        return view('form::input', compact('name', 'label', 'parameters', 'entity', 'value'));
    }

    /**
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
     * @param $action
     * @return \Illuminate\Routing\Route|null
     */
    protected function getRouteByAction($action)
    {
        // Route name space can't be used dynamically because it is on protected scope (L5.4)
        // Therefore defined in config file :(
        return \Route::getRoutes()->getByAction($this->getConfig('route_name_space') . '\\' . $action);
    }

    /**
     * @param string $name
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
     *
     * Parameter key in on kebab-case, config key is on snake_case
     *
     * @param array $parameters
     * @return array
     */
    protected function setDefaultFromConfig($parameters)
    {
        $configurableParameters = [
            'control-label-class',
            'label-grid-class',
            'input-grid-class',
        ];

        foreach ($configurableParameters as $configurableParameter) {
            $parameters[$configurableParameter] = $this->getConfig(snake_case($configurableParameter));
        }

        return $parameters;
    }

    /**
     * Set parameter from form parameters if it is not set
     *
     * @param array $parameters
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
     * @return mixed
     */
    protected function getConfig($name)
    {
        return config(self::CONFIG_NAME . '.' . $name);
    }
}
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
 * TODO boostrap classes and at config
 * TODO? move html classes etc from template to the class and generate one string
 * TODO email, etc methods for input types
 */
class FormBuilder
{
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
     * TODO move to package config
     *
     * The namespace of controllers
     * Defined at RouteServiceProvider, but can't be used here because it is on protected scope (L5.4)
     *
     * @var string
     */
    protected $routeNameSpace = 'App\Http\Controllers';

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

        if (!isset($parameters['method']) && !empty($this->getRouteMethod())) {
            $parameters['method'] = $this->getRouteMethod();
        }

        if (!isset($parameters['id']) && config('form.generate_id') && !empty($this->getFormId())) {
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

        if (!isset($parameters['id']) && config('form.generate_id') && !empty($this->getInputId($name))) {
            $parameters['id'] = $this->getInputId($name);
        }

        if (!isset($parameters['control-label-class'])) {
            $parameters['control-label-class'] = config('form.control_label_class');
        }

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
        return \Route::getRoutes()->getByAction($this->routeNameSpace . '\\' . $action);
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

            //todo move to config
            $controllerNaming = 'Controller';
            $namePos = strpos($controllerName, $controllerNaming);

            if ($namePos === false) {
                return '';
            }

            return substr($controllerName, 0, $namePos);
        }

        return '';
    }
}
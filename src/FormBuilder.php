<?php

namespace A2design\Form;

use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class FormBuilder
{
    private $view;
    private $session;
    private $request;

    /**
     * @var \Illuminate\Routing\Route|null
     */
    private $route = null;

    /**
     * TODO move to package config
     *
     * The namespace of controllers
     * Defined at RouteServiceProvider, but can't be used here because it is on protected scope (L5.4)
     *
     * @var string
     */
    private $routeNameSpace = 'App\Http\Controllers';

    /**
     * Instance of model for the editing
     *
     * @var Model
     */
    private $entity;

    /**
     * Create a new form builder instance.
     *
     * @param Factory $view
     * @param Session $session
     * @param Request|null $request
     */
    public function __construct(Factory $view, Session $session, Request $request = null)
    {
        $this->view = $view;
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
        $this->entity = $entity;

        if (!isset($parameters['method']) && !empty($this->getRouteMethod())) {
            $parameters['method'] = $this->getRouteMethod();
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
     * @return string
     */
    private function getRouteMethod()
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
    private function getRouteByAction($action)
    {
        return \Route::getRoutes()->getByAction($this->routeNameSpace . '\\' . $action);
    }
}
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
    private $csrfToken;
    private $request;

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
        $this->csrfToken = $session->token();
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
        $this->entity = $entity;

        return view('form::form-create', compact('action', 'parameters', 'entity'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function end()
    {
        return view('form::form-end');
    }
}
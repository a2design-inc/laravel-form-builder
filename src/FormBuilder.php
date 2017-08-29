<?php

namespace A2design\Form;

use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;

class FormBuilder
{
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

    public function create()
    {
        return view('form::form');
    }
}
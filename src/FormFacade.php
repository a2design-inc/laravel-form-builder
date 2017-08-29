<?php

namespace A2design\Form;

use Illuminate\Support\Facades\Facade;

/**
 * @see \A2design\Form\FormBuilder
 */
class FormFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return FormBuilder::class;
    }
}
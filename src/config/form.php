<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Generate ids for elements
    |--------------------------------------------------------------------------
    |
    | When enabled the ids for inputs, labels, wrappers etc are generated
    | based on entity name, field name and controller method
    |
    | Supported: true, false
    |
    */

    'generate_id' => true,

    /*
    |--------------------------------------------------------------------------
    | Class for form group
    |--------------------------------------------------------------------------
    |
    | Redefine the default bootstrap "form-group" class or disable it
    |
    | Supported: false, 'string'
    |
    */

    'form_group_class' => 'form-group',

    /*
    |--------------------------------------------------------------------------
    | Class for form direction
    |--------------------------------------------------------------------------
    |
    | Redefine the default bootstrap "form-horizontal" class or disable it
    |
    | Supported: false, 'string'
    |
    */

    'form_direction_class' => 'form-horizontal',

    /*
    |--------------------------------------------------------------------------
    | Class for for input
    |--------------------------------------------------------------------------
    |
    | Redefine the default bootstrap "form-control" class or disable it
    |
    | Supported: false, 'string'
    |
    */

    'form_control_class' => 'form-control',

    /*
    |--------------------------------------------------------------------------
    | Class for for button
    |--------------------------------------------------------------------------
    |
    | Redefine the default bootstrap "btn" class or disable it
    |
    | Supported: false, 'string'
    |
    */

    'btn_class' => 'btn',

    /*
    |--------------------------------------------------------------------------
    | Class for each label
    |--------------------------------------------------------------------------
    |
    | Redefine the default bootstrap "control-label" class or disable it
    |
    | Supported: false, 'string'
    |
    */

    'control_label_class' => 'control-label',

    /*
    |--------------------------------------------------------------------------
    | Class for form group with error
    |--------------------------------------------------------------------------
    |
    | Redefine the default bootstrap "has-error" class or disable it
    |
    | Supported: false, 'string'
    |
    */

    'error_form_group_class' => 'has-error',

    /*
    |--------------------------------------------------------------------------
    | Class for input with error
    |--------------------------------------------------------------------------
    |
    | Define the class or disable it
    |
    | Supported: false, 'string'
    |
    */

    'error_class' => 'has-error',

    /*
    |--------------------------------------------------------------------------
    | Class for grid column with label
    |--------------------------------------------------------------------------
    |
    | Some class name for the grid
    |
    | Supported: false, 'string'
    |
    */

    'label_grid_class' => 'col-md-4',

    /*
    |--------------------------------------------------------------------------
    | Class for grid column with input
    |--------------------------------------------------------------------------
    |
    | Some class name for the grid
    |
    | Supported: false, 'string'
    |
    */

    'input_grid_class' => 'col-md-8',

    /*
    |--------------------------------------------------------------------------
    | Class for grid column with button
    |--------------------------------------------------------------------------
    |
    | Some class name for the grid
    |
    | Supported: false, 'string'
    |
    */

    'button_grid_class' => 'col-md-8 col-md-offset-4',

    /*
    |--------------------------------------------------------------------------
    | The namespace of controllers
    |--------------------------------------------------------------------------
    |
    | Defined at RouteServiceProvider
    |
    | Supported: 'string'
    |
    */

    'route_name_space' => 'App\Http\Controllers',

    /*
    |--------------------------------------------------------------------------
    | The name of controllers
    |--------------------------------------------------------------------------
    |
    | End of your controllers' names
    | By default is just "Controller" when you use ArticleController, UserController etc
    |
    | Supported: 'string'
    |
    */

    'controller_naming' => 'Controller',
];

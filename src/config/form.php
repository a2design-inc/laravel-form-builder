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
    | Use grid classed
    |--------------------------------------------------------------------------
    |
    | When enabled the labels and inputs is located in different columns
    |
    | Supported: true, false
    |
    */

    'use_grid' => true,

    /*
    |--------------------------------------------------------------------------
    | Use bootstrap classes
    |--------------------------------------------------------------------------
    |
    | When enabled the elements have bootstrap classes.
    | You can redefine all of theme separately if you need.
    | This is shortcut for disabling.
    |
    | Supported: true, false
    |
    */

    'bootstrap' => true,

    /*
    |--------------------------------------------------------------------------
    | Use input wrapper
    |--------------------------------------------------------------------------
    |
    | Use wrap div for input or not
    |
    | Supported: true, false
    |
    */

    'wrapper' => true,

    /*
    |--------------------------------------------------------------------------
    | Use form group wrapper
    |--------------------------------------------------------------------------
    |
    | Use wrap div for label and input or not
    |
    | Supported: true, false
    |
    */

    'form_group_wrapper' => true,

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

    'btn_class' => 'btn btn-primary',

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

    'input_grid_class' => 'col-md-6',

    /*
    |--------------------------------------------------------------------------
    | Class for grid offset
    |--------------------------------------------------------------------------
    |
    | Offset for inputs without label column
    |
    | Supported: false, 'string'
    |
    */

    'offset_input_grid_class' => 'col-md-6 col-md-offset-4',

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

    /*
    |--------------------------------------------------------------------------
    | Additional text after label. For example ":"
    |--------------------------------------------------------------------------
    |
    | Supported: false, 'string'
    |
    */

    'label_after' => ':',
];

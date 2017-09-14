# Laravel Form Builder

Laravel plugin for quick form creation with Bootstrap support. 

You can display validation errors, old input values and form context based on model entity without any line of code and with great flexibility. 

![example_image](https://i.imgur.com/NbsZjzE.png)

## Table of Contents

- [Quick example](#quick-example)
- [How to install](#how-to-install)
- [Customization](#customization)
    * [Parameters](#parameters)
    * [Template editing](#template-editing)
    * [Configs](#configs)
    * [Disable Bootstrap/Grid](#disable-bootstrapgrid)
- [Examples](#examples)
- [Full list of parameters](#full-list-of-parameters)
- [Full list of configs](#full-list-of-configs)
- [List of methods](#list-of-methods)
- [Tests](#tests)

## Quick example

Your code:

```
{!! Form::create('ArticleController@update', $article) !!}
    {!! Form::input('name', 'Name') !!}
    {!! Form::buttonGroup() !!}
        {!! Form::buttonLink('Cancel', '/') !!}
        {!! Form::reset() !!}
        {!! Form::submit() !!}
    {!! Form::buttonGroupEnd() !!}
{!! Form::end() !!}
```

Output:

```HTML
<form method="post" action="/article/1" id="update-article" class="form-horizontal">

    <input type="hidden" name="_token" value="CZLvWLRwqvjvZIwiaRcMj0JxyIwvpZ0nZ5y4StwO"> 
    <input type="hidden" name="_method" value="PUT"> 

    <div class="form-group">
        <label for="update-article-name" class="col-md-4 control-label">
            Name
        </label>
        <div class="col-md-6">
            <input id="update-article-name" name="name" value="Some name" class="form-control">
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
        
            <a id="update-article-cancel" href="/" class="btn btn-primary">
                Cancel
            </a>
            
            <button id="update-article-reset" type="reset" class="btn btn-primary">
                Reset
            </button>
            
            <button id="update-article-submit" type="submit" class="btn btn-primary">
                Submit
            </button>
            
        </div>
    </div>
</form>
```

Of course, you can disable bootstrap, set global configs, edit templates etc! See below.

## How to install

Install the package:

```Bash
composer require a2design-inc/laravel-form-builder
```

Register the provider (config/app.php):

```PHP
A2design\Form\FormServiceProvider::class,
```

And the alias:

```PHP
'Form' => A2design\Form\FormFacade::class,
```

Publish the package assets (if you need to change the config file or templates):

```Bash
php artisan vendor:publish
```

## Customization

![example_image](https://i.imgur.com/lmW0rPS.png)

### Parameters

You can specify a lot of additional parameters for every element:

```
{!! Form::create('', null, [

    //set custom method url
    'url' => 'http://google.com',
    
    //your class
    'class' => 'my-form',
    
    //etc
    
]) !!}
```

See the [full list of parameters](#full-list-of-parameters)

### Template editing

The package is used the laravel blade templates for all form elements. Feel free to customize what you need.

Don't forget to publish the package assets for this:

```Bash
php artisan vendor:publish
```

### Configs

Edit the config/form.php file to change the global settings. Don't forget to publish the package assets for this:

```Bash
php artisan vendor:publish
```

See the [full list of configs](#full-list-of-configs)

### Disable Bootstrap/Grid
 
You can remove some class or wrapper for a single element via the $parameters or globally in the config file. You also can redefine grid columns.
 
Or you can use shortcuts which disable few parameters simultaneously
 
```PHP
     //remove all bootsrap classes
     'bootstrap' => false,
     //or just show without grid
     'use-grid' => false,
```
 
## Examples

### Checkbox with label in different column instead next to
 
```
    {!! Form::checkbox('field', 'Field', ['label' => true]) !!}
```

### Line of checkboxes
 
```
    {!! Form::inputGroup(['checkbox-label-class' => 'checkbox-inline']) !!}
        {!! Form::checkbox('foo', 'Foo') !!}
        {!! Form::checkbox('bar', 'Bar') !!}
    {!! Form::inputGroupEnd() !!}
```

### Line of checkboxes with overall label in separated column
 
```
    {!! Form::inputGroup([
        'checkbox-label-class' => 'checkbox-inline',
        'label-text' => 'test',
    ]) !!}
        {!! Form::checkbox('foo', 'Foo') !!}
        {!! Form::checkbox('bar', 'Bar') !!}
    {!! Form::inputGroupEnd() !!}
```

### Line of buttons
 
```
    {!! Form::buttonGroup(['label-text' => 'Some label']) !!}
        {!! Form::buttonLink('Cancel', '/') !!}
        {!! Form::reset() !!}
        {!! Form::submit() !!}
    {!! Form::buttonGroupEnd() !!}
```

### Required input asterisk
 
```CSS
    .form-group.required .control-label:after {
        content: "*";
        color: red;
    }
```

### Post link
 
```
    {!! Form::postLink('ArticleController@destroy', 'Delete', $article) !!}
```

### Radio
 
```
    {!! Form::radio('field', 'Select the value', [
        'inline' => true,
        'options' => [
            '1' => 'First',
            '2' => 'Second',
        ]
    ]) !!}
```

## Full list of parameters
    
| Element          | Parameter               | Description                                  |
|------------------|-------------------------|----------------------------------------------|
| Everywhere       | class                   | Class attribute                              |
|                  | id                      | Id attribute. Generated automatically. If you don't need, specify an empty string or redefine by id what you want. Or just adjust globally in config file |
|                  | use-grid                | Set false to disable the grid classes        |
|                  | bootstrap               | Set false to disable the bootstrap using     |
| Form::create     |                         | Note: you can use other parameters here to apply them to all inputs, buttons etc inside the form |
|                  | method                  | POST, GET, PUT etc                           |
|                  | absolute                | Absolute path of the method                  |
|                  | url                     | Use the url instead action argument          |
|                  | form-direction-class    | Use some class for label->input direction    |
|                  | attrs                   | Array of any attributes: attribute-name => value |
|                  | enctype                 | Set the enctype attribute                    |
|                  | has-files               | Set the enctype attribute to "multipart/form-data" |
|                  | file                    | Shortcut for "has-files"                     |
| Form::input      | all-errors              | List all of the validation errors fot the input instead only first |
|                  | error                   | Set text of error or false to force hide     |
|                  | attrs                   | Array of any attributes: attribute-name => value |
|                  | required                | Set true for the attribute using             |
|                  | readonly                | Set true for the attribute using             |
|                  | disabled                | Set true for the attribute using             |
|                  | autofocus               | Set true for the attribute using             |
|                  | type                    | Set the type attribute                       |
|                  | wrapper-class           | Set the class of the input wrapper div       |
|                  | form-group-wrapper-class| Set the class of the form-group wrapper div  |
|                  | label-class             | Set the class of the label                   |
|                  | label-escaped           | Set false to turn off the string escaping    |
|                  | value                   | Define your own value                        |
|                  | wrapper                 | Set false if you don't need the wrapper div  |
|                  | form-group-wrapper      | Set false if you don't need the wrapper div  |
|                  | label                   | Set false if you don't need the label or set some string with HTML |
|                  | label-after             | Some string instead default ":"              |
|                  | control-label-class     | Redefine the default bootstrap class for each label ("control-label") or disable it |
|                  | form-group-class        | Specify any class instead the "form-group" or set false |
|                  | form-control-class      | Specify any class for the input instead the "form-control" or set false |
|                  | label-grid-class        | Specify any class for the label grid column or set false to use without grid |
|                  | input-grid-class        | Specify any class for the input grid column or set false to use without grid |
|                  | offset-input-grid-class | Offset for inputs without label column       |
|                  | error-form-group-class  | Specify any class for the form group with error or set false |
|                  | error-class             | Specify any class for the input with error or set false |
|                  | only-input              | Set true to disable any wrappers             |
|                  | input-group             | Add string with bootstrap input group or any HTML what you want to add before the input |
| Form::button     | type                    | Set the type attribute                       |
|                  | name                    | Set the name attribute                       |
|                  | value                   | Set the value attribute                      |
|                  | form                    | Set the form attribute                       |
|                  | autofocus               | Set true for the attribute using             |
|                  | disabled                | Set true for the attribute using             |
|                  | escaped                 | Set true/false for the button text escaping  |
|                  | form-group-wrapper      | Set false if you don't need the wrapper div  |
|                  | form-group-wrapper-class| Set the class of the form-group wrapper div  |
|                  | wrapper-class           | Set the class of the input wrapper div       |
|                  | btn-class               | Specify any class for the input instead the default "btn" or set false |
|                  | label                   | Set false (default) if you don't need the label or set some string with HTML |
|                  | label-text              | Set some text for the label                  |
| Form::inputGroup |                         | The group is the several inputs inside one inputs wrappers. So, you can use any input parameters here to describe the label, wrappers etc |
|                  | label-text              | Label text for the group                                        |
| Form::buttonGroup|                         | The group is the several buttons inside one button wrappers. So, you can use any button parameters here to describe the label, wrappers etc |
| Form::buttonLink |                         | Similar to the button, but with "href" ant "target"|
| Form::hidden     |                         | Similar to the input, but with hardcoded "only-input" ant "type"|
| Form::checkbox   |                         | Similar to the input, but with additional parameters            |
|                  | checked                 | True/false                                                      |
|                  | checkbox-label          | The check box also have wrapping label, set false to disable    |
|                  | checkbox-label-class    | Define class for the label around the checkbox                  |
|                  | label                   | Set true to use label position like as usual input label        |
| Form::select     |                         | Similar to the input, but with additional parameters            |
|                  | empty                   | Allow empty option. True/false or text for empty option         |
|                  | options                 | Array value => displayed text                                   |
|                  | multiple                | True/false                                                      |
|                  | size                    | Set the size attribute                                          |
|                  | value                   | You can set array of selected values instead only one 'value' attribute for the multiple|
|                  | use-old                 | True/false. By default it is true and the old values is more important that your 'value' parameter. |
| Form::textarea   |                         | The save as input but with additional parameters |
|                  | maxlength               | Set the maxlength attribute                                     |
|                  | placeholder             | Set the placeholder attribute                                   |
|                  | cols                    | Set the cols attribute                                          |
|                  | rows                    | Set the rows attribute                                          |
| Form::postLink   |                         | Link for post data                                              |
|                  | message                 | Confirmation message                                            |
|                  | enctype                 | Set the enctype attribute manually                              |
|                  | method                  | POST, GET, PUT etc                                              |
|                  | absolute                | Absolute path of the method                                     |
|                  | url                     | Use the url instead action argument                             |
|                  | escaped                 | Set true/false for the text escaping                            | 
| Form::radio      |                         | Similar to the input, but with additional parameters            |
|                  | options                 | Array value => displayed text                                   |
|                  | inline                  | Show the radio buttons in line                                  |
|                  | radio-label-class       | Set class for each radio button label                           |
|                  | escaped                 | Set true/false for the text escaping                            |
|                  | radio-label             | The each radio button has own label, set false to disable       |

## Full list of configs

| Config | Values | Default | Meaning | Description |
|--------|--------|---------|---------|-------------|
| generate_id | true, false | true | Generate ids for elements | When enabled the ids for inputs, labels, wrappers etc are generated based on entity name, field name and controller method |
| control_label_class | false, 'string' | 'control-label' | Class for each label | Redefine the default bootstrap "control-label" class or disable it |
| label_grid_class | false, 'string' | 'col-md-4' | Class for grid column with label | Some class name for the grid |
| input_grid_class | false, 'string' | 'col-md-6' | Class for grid column with input | Some class name for the grid |
| route_name_space | 'string' | 'App\Http\Controllers' | The namespace of controllers | Defined at RouteServiceProvider |
| controller_naming | 'string' | 'Controller' | The name of controllers | End of your controllers' names By default is just "Controller" when you use ArticleController, UserController etc |
| form_group_class | false, 'string' | 'form-group' | Class for form group | Redefine the default bootstrap "form-group" class or disable it |
| form_control_class | false, 'string' | 'form-control' | Class for for input | Redefine the default bootstrap "form-control" class or disable it |
| error_form_group_class |false, 'string' | 'has-error' | Class for form group with error | Redefine the default bootstrap "has-error" class or disable it |
| error_class | false, 'string' | 'has-error' | Class for input with error | Define the class or disable it |
| btn_class | false, 'string' | 'btn btn-primary' | Class for for button | Redefine the default bootstrap "btn" class or disable it |
| offset_input_grid_class | false, 'string' | 'col-md-6 col-md-offset-4' | Class for grid offset | Offset for inputs without label column |
| form_direction_class | false, 'string' | 'form-horizontal' | Class for form direction | Redefine the default bootstrap "form-horizontal" class or disable it |
| use_grid | true, false | true | Use grid classed | When enabled the labels and inputs is located in different columns |
| bootstrap | true, false | true | Use bootstrap classes | When enabled the elements have bootstrap classes. You can redefine all of theme separately if you need. This is shortcut for disabling. |
| wrapper | true, false | true | Use bootstrap classed | Use wrap div for input or not |
| form_group_wrapper | true, false | true | Use form group wrapper | Use wrap div for label and input or not |
| label_after | false, 'string' | ':' | Additional text after label. For example ":" | |

## List of methods

#### Elements

 - Form::create($action = '', $entity = null, $parameters = [])
 - Form::end()
 - Form::input($name, $label = '', $parameters = [])
 - Form::hidden($name, $parameters = [])
 - Form::buttonGroup($parameters = [])
 - Form::buttonGroupEnd()
 - Form::button($text = 'Submit', $parameters = [])
 - Form::buttonLink($text = 'Cancel', $link = '/', $parameters = [])
 - Form::checkbox($name, $label = '', $parameters = [])
 - Form::postLink($action = '', $text = '', $entity = null, $parameters = [])
 - Form::radio($name, $label = '', $parameters = [])

#### Shortcuts
 
 Equal to input() with the "type" parameter
 
 - Form::text() - input() alias
 - Form::password()
 - Form::color()
 - Form::date()
 - Form::datetime()
 - Form::datetimeLocal()
 - Form::email()
 - Form::number()
 - Form::range()
 - Form::search()
 - Form::tel()
 - Form::time()
 - Form::url()
 - Form::month()
 - Form::week()
 - Form::file()
 
 Other:
 
 - Form::reset() - Form::button() with type reset
 - Form::submit() - Form::button() alias
 - Form::text() - Form::textarea() alias
 
## Tests

If you are contributor don't forget run/write tests for any updates!

![example_image](https://i.imgur.com/XwBR0O5.png)

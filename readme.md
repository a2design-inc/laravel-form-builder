# Laravel Form Builder

Laravel plugin for quick form creation with Bootstrap support. 

You can show validation errors, old input values and form context based on model entity without any line of code. 

And you can simply redefine or adjust all what you want.

## Basic example

Your code:

```
{!! Form::create('ArticleController@update', $article) !!}
{!! Form::input('name', 'Name') !!}
{!! Form::end() !!}
```

Output:

```
<form method="post" action="/article/1" id="update-article" > 
    <input type="hidden" name="_token" value="P6LpFJ0bZf4s9aKOi8pSoZXTMITDxtRtQ98qF4wZ"> 
    <input type="hidden" name="_method" value="PUT"> 
    
    <div class="form-group ">
        <label for="update-article-name" class="col-md-4 control-label">
            Name
        </label>
        <div class="col-md-6">
            <input id="update-article-name" class="form-control" name="name" value="Name of the article">
        </div>
    </div> 
</form>
```

## How to install

Register the provider (config/app.php):

```
A2design\Form\FormServiceProvider::class,
```

And the alias:

```
'Form' => A2design\Form\FormFacade::class,
```

Publish the package assets (if you need to change the config file or templates):

```
php artisan vendor:publish
```

## Customization

### Parameters

You can specify a lot of additional parameters:

```
{!! Form::create('', null, [
    //set custom method url
    'url' => 'http://google.com',
    //set method
    'method' => 'POST',
    //disable grid system and show inputs traditionally
    'label-grid-class' => false,
    'input-grid-class' => false,
    //etc
]) !!}

{!! Form::input('', '', ['label' => false]) !!}
```

### Full list of parameters
    
| Element          | Parameter               | Description                                  |
|------------------|-------------------------|----------------------------------------------|
| Everywhere       | class                   | Class attribute                              |
|                  | id                      | Id attribute. Generated automatically. If you don't need, specify empty string or redefine by id what you want. Or just adjust globally in config file |
| Form::create     |                         | Note: you can use input parameters here to apply them for all inputs inside the form |
|                  | method                  | POST, GET, PUT etc                           |
|                  | absolute                | Absolute path of the method                  |
|                  | url                     | Use the url instead action argument          |
| Form::input      | all-errors              | List all of the validation errors fot the input instead only first |
|                  | error                   | Set text of error or false to hide           |
|                  | required                | Set true for the attribute using             |
|                  | autofocus               | Set true for the attribute using             |
|                  | type                    | Set the type attribute                       |
|                  | wrapper-class           | Set the class of the input wrapper div       |
|                  | form-group-wrapper-class| Set the class of the form-group wrapper div  |
|                  | label-class             | Set the class of the label                   |
|                  | value                   | Define your own value                        |
|                  | wrapper                 | Set false if you don't need the wrapper div  |
|                  | form-group-wrapper      | Set false if you don't need the wrapper div  |
|                  | label                   | Set false if you don't need the label or set some string with HTML |
|                  | control-label-class     | Redefine the default bootstrap class for each label ("control-label") or disable it |
|                  | form-group-class        | Specify any class instead the "form-group" or set false |
|                  | label-grid-class        | Specify any class for the label grid column or set false to use without grid |
|                  | input-grid-class        | Specify any class for the input grid column or set false to use without grid |

### Template editing

The package is used the laravel blade templates for all form elements. Feel free to customize what you need.

Don't forget to publish the package assets for this:

```
php artisan vendor:publish
```

### Configs

Edit the config/form.php file to change the global settings (see description for any configuration in the file)

Don't forget to publish the package assets for this:

```
php artisan vendor:publish
```

| Config | Values | Meaning | Description |
|--------|--------|---------|-------------|
| generate_id | true, false | Generate ids for elements | When enabled the ids for inputs, labels, wrappers etc are generated based on entity name, field name and controller method |
| control_label_class | false, 'string' | Class for each label | Redefine the default bootstrap "control-label" class or disable it |
| label_grid_class | false, 'string' | Class for grid column with label | Some class name for the grid |
| input_grid_class | false, 'string' | Class for grid column with input | Some class name for the grid |
| route_name_space | 'string' | The namespace of controllers | Defined at RouteServiceProvider |
| controller_naming | 'string' | The name of controllers | End of your controllers' names By default is just "Controller" when you use ArticleController, UserController etc |

### List of methods

#### Elements

 - Form::create($action = '', $entity = null, $parameters = [])
 - Form::end()
 - Form::input($name, $label = '', $parameters = [])

 
 #### Types shortcuts
 
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
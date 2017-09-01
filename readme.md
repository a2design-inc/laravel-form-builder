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

You can specify additional parameters:

```
{!! Form::create('', null, ['url' => 'http://google.com',]) !!}

{!! Form::input('', '', ['label' => false]) !!}
```    
    
| Element          | Parameter               | Description                                  |
|------------------|-------------------------|----------------------------------------------|
| Everywhere       | class                   | Class attribute                              |
|                  | id                      | Id attribute. Generated automatically. If you don't need, specify empty string or redefine by id what you want. Or just adjust globally in config file |
| Form::create     |                         | Note: you can use input parameters here to apply them for all inputs inside the form |
|                  | method                  | POST, GET, PUT etc                           |
|                  | absolute                | Absolute path of the method                  |
|                  | url                     | Use the url instead action argument          |
|                  | input-wrappers          | TODO                                         |
|                  | labels                  | TODO                                         |
|                  | bootstrap-grids         | TODO                                         |
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
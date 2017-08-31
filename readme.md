# Laravel Form Builer

Laravel plugin for quick form creation. 

You can show validation errors, old input values and form context based on model entity without any line of code. 

And you can simply redefine or adjust all what you want.

## Quick example

Your code:

```
{!! Form::create('ArticleController@update', $article) !!}
{!! Form::end() !!}
```

Output:

```
<form method="post" action="/article/1" id="update-article" > 
    <input type="hidden" name="_token" value="P6LpFJ0bZf4s9aKOi8pSoZXTMITDxtRtQ98qF4wZ"> 
    <input type="hidden" name="_method" value="PUT"> 
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

Publish the package assets:

```
php artisan vendor:publish
```

## Customization

### Parameters

You can specify additional settings as array of parameters:

```
{!! Form::create('', null, ['url' => 'http://google.com',]) !!}

{!! Form::input('', '', ['label' => false]) !!}
```    
    
| Element          | Parameter      | Description                            |
|------------------|----------------|----------------------------------------|
| Everywhere       | class          | Class attribute                        |
|                  | id             | Id attribute. Generated automatically. If you don't need, specify empty string or redefine by id what you want. |
| Form::create     | method         | POST, GET, PUT etc                     |
|                  | absolute       | Absolute path of the method            |
|                  | url            | Use the url instead action argument    |
| Form::input      | all-errors     | List all of the validation errors fot the input instead only first |
|                  | required       | Set true for the attribute using       |
|                  | autofocus      | Set true for the attribute using       |
|                  | type           | Set the type attribute                 |
|                  | wrapper-class  | Set the class of the input wrapper div |
|                  | label-class    | Set the class of the label             |
|                  | value          | Define your value             |

### Template editing

The package is used the laravel blade templates for all form elements. Feel free for customization if you need.

### Configs

Edit the config/form.php file to change the global settings (see description for any configuration in the file)

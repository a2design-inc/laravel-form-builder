# Laravel Form Builer

Laravel plugin for quick form creation. 

### Why

You can show validation errors, old input values and form context based on model entity without any line of code. 

And you can redefine all what you want manually.

### Quick example

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

### How to install

Register the provider (config/app.php):

```
A2design\Form\FormServiceProvider::class,
```

And the alias:

```
'Form' => A2design\Form\FormFacade::class,
```

### Customization

#### Parameters

You can specify additional parameters:

```
{!! Form::create('', null, [
    'url' => 'http://google.com',
]) !!}
```

    - method
    - absolute
    - url
    - id
    - class
```

{!! Form::input('', '', [
    'label' => false
]) !!}
```
    - label
    - id
    - all-errors
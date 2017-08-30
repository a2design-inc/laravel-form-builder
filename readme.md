# Laravel Form Builer

Laravel plugin for quick form creation. 

### What is it

You can show validation errors, old input values and form context based on model entity without any line of code. 
Or you can redefine all what you want manually.

### Quick example

```
{!! Form::create('ArticleController@update', $article) !!}
{!! Form::end() !!}
```

Output

```
<form method="post" action="/Article/1" id="update-article" > 
<input type="hidden" name="_token" value="P6LpFJ0bZf4s9aKOi8pSoZXTMITDxtRtQ98qF4wZ"> 
</form>
```

### How to install

Register the provider (config/app.php):

```
A2design\Form\FormServiceProvider::class,
```

Add the alias (config/app.php):

```
'Form' => A2design\Form\FormFacade::class,
```

### How to use

```
{!! Form::create() !!}
```

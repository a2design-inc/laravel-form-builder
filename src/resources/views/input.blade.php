@extends('form::layouts.input')

@section('input')
<?php var_dump($parameters) ;?>
    <input
        @if (isset($parameters['id']))
            id="{!! $parameters['id'] !!}"
        @endif

        class="form-control
            @if (isset($parameters['class']))
               {!! $parameters['class'] !!}
            @endif
        "

        name="{!! $name !!}"
        value="{!! $value !!}"

        @if (isset($parameters['type']))
            type="{!! $parameters['type'] !!}"
        @endif

        @if (isset($parameters['required']) && $parameters['required'] === true)
            required
        @endif

        @if (isset($parameters['autofocus']) && $parameters['autofocus'] === true)
            autofocus
        @endif
    >
@endsection
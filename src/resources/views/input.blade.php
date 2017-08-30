@extends('form::layouts.input')

@section('input')
    <input
        @if (isset($parameters['id']))
            id="{!! $parameters['id'] !!}"
        @endif
        type="text"
        class="form-control"
        name="{!! $name !!}"
        value="{!! $value !!}"
        required
        autofocus
    >
@endsection
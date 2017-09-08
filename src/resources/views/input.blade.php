@extends('form::layouts.input')

@section('input')
    <input
        @include('form::partials.custom-attributes')

        @if (!empty($parameters['id']))
            id="{{ $parameters['id'] }}"
        @endif

        @if (!empty($parameters['input-classes']))
            class="{{ $parameters['input-classes'] }}"
        @endif

        @if (!empty($parameters['value']) && is_scalar($parameters['value']))
            value="{!! $parameters['value'] !!}"
        @endif

        @if (!empty($parameters['type']))
            type="{{ $parameters['type'] }}"
        @endif

        @if (!empty($parameters['type']) && $parameters['type'] == 'file' && !empty($parameters['multiple']))
            multiple
            name="{{ $name }}[]"
        @else
            name="{{ $name }}"
        @endif

        @if (isset($parameters['required']) && $parameters['required'] === true)
            required
        @endif

        @if (isset($parameters['autofocus']) && $parameters['autofocus'] === true)
            autofocus
        @endif

        @if (isset($parameters['disabled']) && $parameters['disabled'] === true)
            disabled
        @endif

        @if (isset($parameters['readonly']) && $parameters['readonly'] === true)
            readonly
        @endif
    >
{{--For multiple layout using--}}
@overwrite
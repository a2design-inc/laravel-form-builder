@extends('form::layouts.input')

@section('input')
    @if (!empty($parameters['input-group']))
        {!! $parameters['input-group'] !!}
    @endif

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

        @if (!empty($parameters['required']))
            required
        @endif

        @if (!empty($parameters['autofocus']))
            autofocus
        @endif

        @if (!empty($parameters['disabled']))
            disabled
        @endif

        @if (!empty($parameters['readonly']))
            readonly
        @endif
    >
{{--For multiple layout using--}}
@overwrite
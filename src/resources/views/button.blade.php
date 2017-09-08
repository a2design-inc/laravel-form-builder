@extends('form::layouts.input')

@section('input')
    <button
        @include('form::partials.custom-attributes')

        @if (!empty($parameters['id']))
            id="{{ $parameters['id'] }}"
        @endif

        @if (!empty($parameters['button-classes']))
            class="{{ $parameters['button-classes'] }}"
        @endif

        @if (!empty($parameters['type']))
            type="{{ $parameters['type'] }}"
        @endif

        @if (!empty($parameters['name']))
            name="{{ $parameters['name'] }}"
        @endif

        @if (!empty($parameters['value']) && is_scalar($parameters['value']))
            value="{!! $parameters['value'] !!}"
        @endif

        @if (!empty($parameters['form']))
            form="{{ $parameters['form'] }}"
        @endif

        @if (!empty($parameters['autofocus']))
            autofocus
        @endif

        @if (!empty($parameters['disabled']))
            disabled
        @endif
    >
        @if ($parameters['escaped'])
            {{ $parameters['text'] }}
        @else
            {!! $parameters['text'] !!}
        @endif
    </button>
{{--For multiple layout using--}}
@overwrite
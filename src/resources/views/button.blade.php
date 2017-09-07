@extends('form::layouts.input')

@section('input')
    <button
        @if (!empty($parameters['id']))
            id="{!! $parameters['id'] !!}"
        @endif

        @if (!empty($parameters['button-classes']))
            class="{!! $parameters['button-classes'] !!}"
        @endif

        @if (!empty($parameters['type']))
            type="{!! $parameters['type'] !!}"
        @endif

        @if (!empty($parameters['name']))
            name="{!! $parameters['name'] !!}"
        @endif

        @if (!empty($parameters['value']))
            value="{!! $parameters['value'] !!}"
        @endif

        @if (!empty($parameters['form']))
            form="{!! $parameters['form'] !!}"
        @endif

        @if (isset($parameters['autofocus']) && $parameters['autofocus'] === true)
            autofocus
        @endif

        @if (isset($parameters['disabled']) && $parameters['disabled'] === true)
            disabled
        @endif
    >
        @if ($parameters['escaped'] === true)
            {{ $parameters['text'] }}
        @else
            {!! $parameters['text'] !!}
        @endif
    </button>
{{--For multiple layout using--}}
@overwrite
@extends('form::layouts.input')

@section('input')
    <button
        @if (isset($parameters['id']))
            id="{!! $parameters['id'] !!}"
        @endif

        @if (isset($parameters['input-classes']))
            class="{!! $parameters['input-classes'] !!}"
        @endif

        @if (isset($parameters['type']))
            type="{!! $parameters['type'] !!}"
        @endif

        @if (isset($parameters['name']))
            name="{!! $parameters['name'] !!}"
        @endif

        @if (isset($parameters['value']))
            value="{!! $parameters['value'] !!}"
        @endif

        @if (isset($parameters['form']))
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
            {{ $text }}
        @else
            {!! $text !!}
        @endif
    </button>
{{--For multiple layout using--}}
@overwrite
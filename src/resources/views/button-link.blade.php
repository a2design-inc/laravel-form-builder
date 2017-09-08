@extends('form::layouts.input')

@section('input')
    <a
        @include('form::partials.custom-attributes')

        @if (!empty($parameters['id']))
            id="{{ $parameters['id'] }}"
        @endif

        @if (!empty($parameters['button-classes']))
            class="{{ $parameters['button-classes'] }}"
        @endif

        @if (!empty($parameters['name']))
            name="{{ $parameters['name'] }}"
        @endif

        @if (!empty($parameters['href']))
            href="{{ $parameters['href'] }}"
        @endif

        @if (!empty($parameters['target']))
            target="{{ $parameters['target'] }}"
        @endif
    >
        @if ($parameters['escaped'])
            {{ $parameters['text'] }}
        @else
            {!! $parameters['text'] !!}
        @endif
    </a>
{{--For multiple layout using--}}
@overwrite
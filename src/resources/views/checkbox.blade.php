@extends('form::layouts.input')

@section('input')
    @if (!isset($parameters['checkbox-label']) || $parameters['checkbox-label'] !== false)
        <label
            @if (!empty($parameters['checkbox-label-class']))
                class="{{ $parameters['checkbox-label-class'] }}"
            @endif
        >
    @endif

        <input type="hidden" name="{{ $name }}" value="0">
        <input
            @include('form::partials.custom-attributes')

            @if (!empty($parameters['id']))
                id="{{ $parameters['id'] }}"
            @endif

            @if (!empty($parameters['input-classes']))
                class="{{ $parameters['input-classes'] }}"
            @endif

            name="{{ $name }}"
            value="1"
            type="checkbox"

            @if (!empty($parameters['required']))
                required
            @endif

            @if (!empty($parameters['autofocus']))
                autofocus
            @endif

            @if (!empty($parameters['disabled']))
                disabled
            @endif

            @if (!empty($parameters['checked']))
                checked
            @endif

            @if (!empty($parameters['readonly']))
                readonly
            @endif
        >

    @if (!isset($parameters['checkbox-label']) || $parameters['checkbox-label'] !== false)
            @if ($parameters['label-escaped'])
                {{ $label }}
            @else
                {!! $label !!}
            @endif
        </label>
    @endif
{{--For multiple layout using--}}
@overwrite
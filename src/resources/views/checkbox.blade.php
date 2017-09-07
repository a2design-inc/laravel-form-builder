@extends('form::layouts.input')

@section('input')
    @if (empty($parameters['checkbox-label']) || $parameters['checkbox-label'] !== false)
        <label
            @if (!empty($parameters['checkbox-label-class']))
                class="{!! $parameters['checkbox-label-class'] !!}"
            @endif
        >
    @endif

        <input type="hidden" name="{!! $name !!}" value="0">
        <input
            @if (!empty($parameters['id']))
                id="{!! $parameters['id'] !!}"
            @endif

            @if (!empty($parameters['input-classes']))
                class="{!! $parameters['input-classes'] !!}"
            @endif

            name="{!! $name !!}"
            value="1"
            type="checkbox"

            @if (isset($parameters['required']) && $parameters['required'] === true)
                required
            @endif

            @if (isset($parameters['autofocus']) && $parameters['autofocus'] === true)
                autofocus
            @endif

            @if (isset($parameters['disabled']) && $parameters['disabled'] === true)
                disabled
            @endif

            @if (isset($parameters['checked']) && $parameters['checked'] === true)
                checked
            @endif

            @if (isset($parameters['readonly']) && $parameters['readonly'] === true)
                readonly
            @endif
        >
        @if ($parameters['label'] === false)
            {!! $label !!}
        @endif

    @if (empty($parameters['checkbox-label']) || $parameters['checkbox-label'] !== false)
        </label>
    @endif
{{--For multiple layout using--}}
@overwrite
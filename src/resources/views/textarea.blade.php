@extends('form::layouts.input')

@section('input')
    <textarea
        @if (!empty($parameters['id']))
            id="{{ $parameters['id'] }}"
        @endif

        @if (!empty($parameters['input-classes']))
            class="{{ $parameters['input-classes'] }}"
        @endif

        name="{{ $name }}"

        @if (!empty($parameters['cols']))
            cols="{{ $parameters['cols'] }}"
        @endif

        @if (!empty($parameters['rows']))
            rows="{{ $parameters['rows'] }}"
        @endif

        @if (!empty($parameters['maxlength']))
            maxlength="{{ $parameters['maxlength'] }}"
        @endif

        @if (!empty($parameters['placeholder']))
            placeholder="{{ $parameters['placeholder'] }}"
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
    ><?php if($parameters['escaped']) { ?>{{ $parameters['value'] }}<?php } else { ?>{!! $parameters['value'] !!}<?php } ?></textarea>
{{--For multiple layout using--}}
@overwrite
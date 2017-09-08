@extends('form::layouts.input')

@section('input')
    <textarea
        @include('form::partials.custom-attributes')

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
    ><?php if($parameters['escaped']) { ?>{{ $parameters['value'] }}<?php } else { ?>{!! $parameters['value'] !!}<?php } ?></textarea>
{{--For multiple layout using--}}
@overwrite
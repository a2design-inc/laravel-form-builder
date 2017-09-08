@extends('form::layouts.input')

@section('input')

    <input type="hidden" name="{{ $name }}" value="">

    @foreach($parameters['options'] as $value => $text)
        @if (empty($parameters['inline']))
            <div class="radio">
        @endif
        @if (!isset($parameters['radio-label']) || $parameters['radio-label'] !== false)
            <label
                @if (!empty($parameters['radio-label-class']))
                    class="{{ $parameters['radio-label-class'] }}"
                @endif
            >
        @endif
    
            <input
                @if (!empty($parameters['id']))
                    id="{{ $parameters['id'] . '-' . kebab_case($value) }}"
                @endif

                @if (!empty($parameters['input-classes']))
                    class="{{ $parameters['input-classes'] }}"
                @endif
    
                name="{{ $name }}"
                value="{!! $value !!}"
                type="radio"

                @if (is_scalar($parameters['value']) && $value == $parameters['value'])
                    checked
                @endif
            >
                @if ($parameters['escaped'])
                    {{ $text }}
                @else
                    {!! $text !!}
                @endif

        @if (!isset($parameters['radio-label']) || $parameters['radio-label'] !== false)
            </label>
        @endif
        @if (empty($parameters['inline']))
            </div>
        @endif
    @endforeach
{{--For multiple layout using--}}
@overwrite
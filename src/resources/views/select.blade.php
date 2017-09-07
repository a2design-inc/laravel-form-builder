@extends('form::layouts.input')

@section('input')
    <select
        @if (!empty($parameters['id']))
            id="{{ $parameters['id'] }}"
        @endif

        @if (!empty($parameters['input-classes']))
            class="{{ $parameters['input-classes'] }}"
        @endif

        @if (!empty($parameters['size']))
            size="{{ $parameters['size'] }}"
        @endif

        name="{{ $name }}{{ isset($parameters['multiple']) && $parameters['multiple'] === true ? '[]' : '' }}"

        @if (isset($parameters['required']) && $parameters['required'] === true)
            required
        @endif

        @if (isset($parameters['multiple']) && $parameters['multiple'] === true)
            multiple
        @endif

        @if (isset($parameters['autofocus']) && $parameters['autofocus'] === true)
            autofocus
        @endif

        @if (isset($parameters['disabled']) && $parameters['disabled'] === true)
            disabled
        @endif
    >
        @if(!empty($parameters['empty']))
            <option value="">
                @if (is_string($parameters['empty']))
                    {{ $parameters['empty'] }}
                @endif
            </option>
        @endif
        @foreach($parameters['options'] as $option => $text)
            <option value="{{ $option }}"
                @if (
                    !is_array($parameters['value']) && $option == $parameters['value']
                    || is_array($parameters['value']) && in_array($option, $parameters['value'])
                )
                    selected
                @endif
            >{{ $text }}</option>
        @endforeach
    </select>
    {{--For multiple layout using--}}
@overwrite
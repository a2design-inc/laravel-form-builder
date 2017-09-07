<form
    @include('form::partials.custom-attributes')

    @if (!empty($parameters['form-method']))
        method="{{ $parameters['form-method'] }}"
    @endif

    @if (!empty($parameters['form-action']))
        action="{{ $parameters['form-action'] }}"
    @endif

    @if (!empty($parameters['id']))
        id="{{ $parameters['id'] }}"
    @endif

    @if (!empty($parameters['enctype']))
        enctype="{{ $parameters['enctype'] }}"
    @endif

    @if (!empty($parameters['form-classes']))
        class="{{ $parameters['form-classes'] }}"
    @endif
>
    @if (!empty($parameters['hidden-inputs']))
        {!! $parameters['hidden-inputs'] !!}
    @endif
@if (isset($parameters['only-input']) && $parameters['only-input'])
    @yield('input')
@else

    {{--Set wrapper div--}}
    @if (!isset($parameters['form-group-wrapper']) || $parameters['form-group-wrapper'] !== false)
        <div
            @if (!empty($parameters['form-group-wrapper-classes']))
                class="{{ $parameters['form-group-wrapper-classes'] }}"
            @endif
        >
    @endif

        {{--Set label--}}
        @if (isset($parameters['label']) && is_string($parameters['label']))
            {!! $parameters['label'] !!}
        @elseif (!isset($parameters['label']) || $parameters['label'] !== false)
            <label
                @if (!empty($parameters['id']))
                    for="{{ $parameters['id'] }}"
                @endif
                @if (!empty($parameters['label-classes']))
                    class="{{ $parameters['label-classes'] }}"
                @endif
            >
                @if ($parameters['label-escaped'])
                    {{ $label }}{{ !empty($parameters['label-after']) ? $parameters['label-after'] : '' }}
                @else
                    {!! $label !!}{{ !empty($parameters['label-after']) ? $parameters['label-after'] : '' }}
                @endif

            </label>
        @endif

        {{--Set input wrapping--}}
        @if (!isset($parameters['wrapper']) || $parameters['wrapper'] !== false)
            <div
                @if (!empty($parameters['input-wrapper-classes']))
                    class="{{ $parameters['input-wrapper-classes'] }}"
                @endif
            >
        @endif

            @yield('input')

            {{--Set errors--}}
            @if (!empty($parameters['all-errors']) && (!isset($parameters['error']) || $parameters['error'] !== false))

                @foreach ($errors->get($name) as $message)
                    @include('form::partials.error-message')
                @endforeach

                @foreach ($errors->get($name . '.*') as $message)
                    @include('form::partials.error-message')
                @endforeach

            @elseif (isset($parameters['error']) && $parameters['error'] !== false)
                @include('form::partials.error-message', ['message' => $parameters['error']])
            @elseif (!empty($parameters['error']))
            @elseif ($errors->has($name) && (!isset($parameters['error']) || $parameters['error'] !== false))
                @include('form::partials.error-message', ['message' => $errors->first($name)])
            @endif

        @if (!isset($parameters['wrapper']) || $parameters['wrapper'] !== false)
            </div>
        @endif

    @if (!isset($parameters['form-group-wrapper']) || $parameters['form-group-wrapper'] !== false)
        </div>
    @endif
@endif
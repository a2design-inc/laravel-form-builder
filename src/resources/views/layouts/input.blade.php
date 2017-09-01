{{--Set wrapper div--}}
@if (!isset($parameters['form-group-wrapper']) || $parameters['form-group-wrapper'] !== false)
    <div
        class="
             @if (isset($parameters['form-group-class']) && $parameters['form-group-class'] !== false)
                {!! $parameters['form-group-class'] !!}
             @elseif (!isset($parameters['form-group-class']))
                form-group
             @endif

            @if ($errors->has($name) || isset($parameters['error']) && $parameters['error'] !== false)
                has-error
            @endif

             @if (isset($parameters['form-group-wrapper-class']))
                {!! $parameters['form-group-wrapper-class'] !!}
             @endif
            "
    >
@endif

    {{--Set label--}}
    @if (isset($parameters['label']) && is_string($parameters['label']))
        {!! $parameters['label'] !!}
    @elseif (!isset($parameters['label']) || $parameters['label'] !== false)
        <label
            @if (isset($parameters['id']))
                for="{!! $parameters['id'] !!}"
            @endif
            class="
                 @if (isset($parameters['label-class']))
                    {!! $parameters['label-class'] !!}
                 @endif

                 @if (isset($parameters['label-grid-class']))
                    {!! $parameters['label-grid-class'] !!}
                 @endif

                 @if (isset($parameters['control-label-class']) && $parameters['control-label-class'] !== false)
                    {!! $parameters['control-label-class'] !!}
                 @endif
            "
        >
            {{ $label }}
        </label>
    @endif

    {{--Set input wrapping--}}
    @if (!isset($parameters['wrapper']) || $parameters['wrapper'] !== false)
        <div class="
             @if (isset($parameters['input-grid-class']))
                {!! $parameters['input-grid-class'] !!}
             @endif

            @if (isset($parameters['wrapper-class']))
                {!! $parameters['wrapper-class'] !!}
            @endif
        ">
    @endif

        @yield('input')

        {{--Set errors--}}
        @if (isset($parameters['all-errors']) && $parameters['all-errors'] === true)

            @foreach ($errors->get($name) as $message)
                @include('form::partials.error-message')
            @endforeach

            @foreach ($errors->get($name . '.*') as $message)
                @include('form::partials.error-message')
            @endforeach

        @elseif (isset($parameters['error']) && $parameters['error'] !== false)
            @include('form::partials.error-message', ['message' => $parameters['error']])
        @elseif (isset($parameters['error']) && $parameters['error'] === false)
        @elseif ($errors->has($name))
            @include('form::partials.error-message', ['message' => $errors->first($name)])
        @endif

    @if (!isset($parameters['wrapper']) || $parameters['wrapper'] !== false)
        </div>
    @endif

@if (!isset($parameters['form-group-wrapper']) || $parameters['form-group-wrapper'] !== false)
    </div>
@endif
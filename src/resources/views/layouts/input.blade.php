@if (!isset($parameters['form-group-wrapper']) || $parameters['form-group-wrapper'] !== false)
    <div
        class="form-group

            {!! $errors->has($name) ? ' has-error' : '' !!}

             @if (isset($parameters['wrapper-class']))
                {!! $parameters['wrapper-class'] !!}
             @endif
            "
    >
@endif

    @if (isset($parameters['label']) && is_string($parameters['label']))
        {!! $parameters['label'] !!}
    @elseif (!isset($parameters['label']) || $parameters['label'] !== false)
        <label
            @if (isset($parameters['id']))
                for="{!! $parameters['id'] !!}"
            @endif
            class="col-md-4 control-label
                 @if (isset($parameters['label-class']))
                    {!! $parameters['label-class'] !!}
                @endif
            "
        >
            {{ $label }}
        </label>
    @endif

    @if (!isset($parameters['wrapper']) || $parameters['wrapper'] !== false)
        <div class="col-md-6">
    @endif
        @yield('input')

        @if (isset($parameters['all-errors']) && $parameters['all-errors'] === true)
            @foreach ($errors->get($name) as $message)
                @include('form::partials.error-message')
            @endforeach

            @foreach ($errors->get($name . '.*') as $message)
                @include('form::partials.error-message')
            @endforeach
        @elseif ($errors->has($name))
            @include('form::partials.error-message', ['message' => $errors->first($name)])
        @endif
    @if (!isset($parameters['wrapper']) || $parameters['wrapper'] !== false)
        </div>
    @endif

@if (!isset($parameters['form-group-wrapper']) || $parameters['form-group-wrapper'] !== false)
    </div>
@endif
<div
    class="form-group

        {{ $errors->has($name) ? ' has-error' : '' }}

         @if (isset($parameters['wrapper-class']))
            {!! $parameters['wrapper-class'] !!}
         @endif
        "
>
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

    <div class="col-md-6">
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
    </div>
</div>
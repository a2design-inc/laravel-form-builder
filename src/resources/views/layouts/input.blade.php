<div class="form-group{{ $errors->has($name) ? ' has-error' : '' }}">
    <label
        @if (isset($parameters['id']))
            for="{!! $parameters['id'] !!}"
        @endif
        class="col-md-4 control-label"
    >
        {{ $label }}
    </label>

    <div class="col-md-6">
        @yield('input')

        @if (isset($parameters['all-errors']) && $parameters['all-errors'] === true)
            @foreach ($errors->get($name) as $message)
                <span class="help-block">
                    <strong>{{ $message }}</strong>
                </span>
            @endforeach

            @foreach ($errors->get($name . '.*') as $message)
                <span class="help-block">
                    <strong>{{ $message }}</strong>
                </span>
            @endforeach
        @endif
    </div>
</div>
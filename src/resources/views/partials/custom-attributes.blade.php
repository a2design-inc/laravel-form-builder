@if (isset($parameters) && isset($parameters['attrs']))
    @foreach ($parameters['attrs'] as $attribute => $value)
        {!! $attribute !!}="{!! $value !!}"
    @endforeach
@endif
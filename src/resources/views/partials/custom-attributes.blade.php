@if (isset($parameters) && isset($parameters['attributes']))
    @foreach ($parameters['attributes'] as $attribute => $value)
        {!! $attribute !!}="{!! $value !!}"
    @endforeach
@endif
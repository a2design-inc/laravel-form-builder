<form
    name="{{ $parameters['id'] }}"

    @if (!empty($parameters['form-method']))
        method="{!! $parameters['form-method'] !!}"
    @endif

    @if (!empty($parameters['form-action']))
        action="{!! $parameters['form-action'] !!}"
    @endif

    @if (!empty($parameters['id']))
        id="{!! $parameters['id'] !!}"
    @endif

    @if (!empty($parameters['enctype']))
        enctype="{!! $parameters['enctype'] !!}"
    @endif
>
    @if (!empty($parameters['hidden-inputs']))
        {!! $parameters['hidden-inputs'] !!}
    @endif

</form>
<a href="javascrit:void(0);" onclick="if (confirm('{{ $parameters['message'] }}')) { document['{{ $parameters['id'] }}'].submit(); } event.returnValue = false; return false;">{{ $parameters['text'] }}</a>
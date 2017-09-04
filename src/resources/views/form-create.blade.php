<form
    {{--set method attribute--}}
    @if (isset($parameters['method']) && ($parameters['method'] === 'get' || $parameters['method'] === 'GET'))
        method="{!! $parameters['method'] !!}"
    @else
        method="post"
    @endif

    @php
        $absolute = true;

        if (!isset($parameters['absolute'])) {
            $absolute = false;
        }
    @endphp

    {{--set action attribute--}}
    @if (isset($parameters['url']))
        action="{!! $parameters['url'] !!}"
    @elseif (!empty($action) && !empty($entity))
        action="{!! action($action, ['id' => $entity->id], $absolute) !!}"
    @elseif (!empty($action))
        action="{!! action($action, [], $absolute) !!}"
    @endif

    {{--set or generate id attribute--}}
    @if (isset($parameters['id']))
        id="{!! $parameters['id'] !!}"
    @endif

    {{--set or generate id attribute--}}
    @if (isset($parameters['enctype']))
        enctype="{!! $parameters['enctype'] !!}"
    @endif

    {{--set class attribute--}}
    @if (isset($parameters['form-classes']))
        class="{!! $parameters['form-classes'] !!}"
    @endif
>
    {{--set hidden inputs--}}
    @if (!isset($parameters['method']) || $parameters['method'] !== 'get' && $parameters['method'] !== 'GET')
        {!! csrf_field() !!}
        @if (isset($parameters['method']) && $parameters['method'] !== 'POST' && $parameters['method'] !== 'post')
            {!! method_field(strtoupper($parameters['method'])) !!}
        @endif
    @endif
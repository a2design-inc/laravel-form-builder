<form
    @if (isset($parameters['method']))
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

    @if (isset($parameters['url']))
        action="{!! $parameters['url'] !!}"
    @elseif (!empty($action) && !empty($entity))
        action="{!! action($action, ['id' => $entity->id], $absolute) !!}"
    @elseif (!empty($action))
        action="{!! action($action, [], $absolute) !!}"
    @endif

    @if (isset($parameters['id']))
        id="{!! $parameters['id'] !!}"
    @elseif (!empty($action) && !empty($entity))
        @php
            $method = explode('@', $action)[1];
            $entityName = (new \ReflectionClass($entity))->getShortName();
            $id = kebab_case($method) . '-' . kebab_case($entityName);
        @endphp
        id="{!! $id !!}"
    @elseif (!empty($action))
        id="{!! kebab_case(explode('@', $action)[1]) !!}"
    @endif

    @if (isset($parameters['class']))
        class="{!! $parameters['class'] !!}"
    @endif
>
    @if (!isset($parameters['method']) || $parameters['method'] !== 'get' && $parameters['method'] !== 'GET')
        {{ csrf_field() }}
    @endif
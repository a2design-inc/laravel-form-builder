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
    @elseif (!empty($action))
        id="{!! (new \ReflectionClass($this))->getShortName() . explode('@', $action)[1] !!}"
    @endif

    @if (isset($parameters['class']))
        class="$parameters['class']"
    @endif
>
@php
    $path = 'assets/img/lcuaa-logo.png';
@endphp

{{--
    The LCUAA mark, always beside the event name, so it is decorative (empty alt). Its dark red fades
    into the dark theme's surface, so there it sits on a small white tile.
--}}
<img src="{{ asset($path) }}?v={{ filemtime(public_path($path)) }}" alt="" width="268" height="512" decoding="async"
    {{ $attributes->merge(['class' => 'w-auto shrink-0 rounded-md dark:bg-white dark:px-1.5 dark:py-1']) }}>

@props(['division', 'number'])

@php
    // Prefer the small copy from `php artisan candidates:photos`, unless the original was replaced after it was made.
    $original = "assets/img/{$division}/{$number}.jpg";
    $web = "assets/img/{$division}/web/{$number}.jpg";
    $path = is_file(public_path($web)) && (! is_file(public_path($original)) || filemtime(public_path($web)) >= filemtime(public_path($original)))
        ? $web
        : $original;
    // The file time in the URL lets the server cache photos for a day without showing a stale one after a swap.
    $version = is_file(public_path($path)) ? filemtime(public_path($path)) : null;
@endphp

{{-- Fixed 4:5 frame so cards stay aligned while photos load or when a file is missing. --}}
<div {{ $attributes->merge(['class' => 'relative aspect-[4/5] overflow-hidden bg-surface-2']) }}>
    <span hidden class="absolute inset-0 grid place-items-center text-sm text-ink-2">No photo on file</span>
    <img src="{{ asset($path) }}{{ $version ? "?v={$version}" : '' }}" alt="{{ $division === 'mr' ? 'Mr.' : 'Ms.' }} LCUAA candidate {{ $number }}"
        width="640" height="1016" loading="lazy" decoding="async" class="absolute inset-0 size-full object-cover"
        onerror="this.previousElementSibling.hidden = false; this.remove()">
</div>

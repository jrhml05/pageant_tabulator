<div {{ $attributes->merge(['class' => 'card overflow-hidden']) }}>
    <div class="overflow-x-auto" data-refresh-region>
        {{ $slot }}
    </div>
</div>

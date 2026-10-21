{{-- Pinned to the bottom of the tablet screen; the judge layout pads <main> so cards clear it. --}}
<div class="fixed inset-x-0 bottom-0 z-20 border-t border-line bg-surface pb-[env(safe-area-inset-bottom)]">
    <div class="mx-auto flex max-w-screen-2xl flex-wrap items-center gap-2 px-4 py-3 sm:px-6">
        {{ $slot }}
    </div>
</div>

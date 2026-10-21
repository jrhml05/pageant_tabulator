@foreach (['success' => 'bg-success-soft text-success-ink', 'error' => 'bg-danger-soft text-danger'] as $key => $tone)
    @if (session()->has($key))
        <div data-dismissible role="{{ $key === 'error' ? 'alert' : 'status' }}"
            class="mb-5 flex items-start justify-between gap-3 rounded-md px-4 py-3 text-sm font-medium {{ $tone }}">
            <span>{{ session($key) }}</span>
            <button type="button" data-dismiss class="-my-1.5 -mr-2 grid size-9 shrink-0 cursor-pointer place-items-center rounded" aria-label="Dismiss message">
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
        </div>
    @endif
@endforeach

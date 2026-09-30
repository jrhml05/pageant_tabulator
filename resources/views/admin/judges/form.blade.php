{{-- Shared by create and edit. `$judge` is null when creating. --}}
@php
    $fields = [
        ['name', 'Name', 'text', 'off', 'Shown on results and printed sheets.'],
        ['username', 'Username', 'text', 'off', 'The judge signs in with this, for example judge1.'],
        ['password', 'Password', 'text', 'new-password', $judge
            ? 'Leave blank to keep the current password. At least 6 characters.'
            : 'At least 6 characters. It is shown here so you can hand it to the judge.'],
    ];
    $panel = old('panel', $judge?->panel);
@endphp

<form action="{{ $action }}" method="POST" class="card max-w-xl" novalidate>
    @csrf
    @if ($judge)
        @method('PUT')
    @endif

    <div class="flex flex-col gap-5 p-5 sm:p-6">
        @foreach ($fields as [$field, $label, $type, $autocomplete, $help])
            <div>
                <label for="{{ $field }}" class="label">{{ $label }}</label>
                <input id="{{ $field }}" name="{{ $field }}" type="{{ $type }}" autocomplete="{{ $autocomplete }}"
                    @if ($field !== 'name') autocapitalize="none" spellcheck="false" @endif
                    value="{{ $field === 'password' ? '' : old($field, $judge?->$field) }}" class="input mt-1.5"
                    aria-describedby="{{ $field }}-help"
                    @error($field) aria-invalid="true" aria-errormessage="{{ $field }}-error" @enderror>
                <p id="{{ $field }}-help" class="mt-1.5 text-sm text-ink-2">{{ $help }}</p>
                @error($field)
                    <p id="{{ $field }}-error" class="field-error">{{ $message }}</p>
                @enderror
            </div>
        @endforeach

        <fieldset @error('panel') aria-invalid="true" aria-errormessage="panel-error" @enderror>
            <legend class="label">Panel</legend>
            <p class="mt-1 text-sm text-ink-2">A judge only sees the segments their panel scores.</p>
            <div class="mt-2 grid gap-2 sm:grid-cols-2">
                @foreach (config('pageant.panels') as $value => $label)
                    @php
                        $scores = collect(config('pageant.segments'))->where('panel', $value)->map(fn ($s) => $s['short'] ?? $s['label'])->join(', ');
                    @endphp
                    <label class="flex cursor-pointer items-start gap-3 rounded-md border border-line-strong p-3 has-checked:border-accent has-checked:bg-accent-soft">
                        <input type="radio" name="panel" value="{{ $value }}" @checked($panel === $value) class="mt-1 size-4 accent-accent">
                        <span>
                            <span class="block font-medium">{{ $label }}</span>
                            <span class="block text-sm text-ink-2">{{ $scores }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
            @error('panel')
                <p id="panel-error" class="field-error">{{ $message }}</p>
            @enderror
        </fieldset>
    </div>

    <div class="flex justify-end gap-2 border-t border-line px-5 py-4 sm:px-6">
        <a href="{{ route('judges.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">{{ $judge ? 'Save changes' : 'Add judge' }}</button>
    </div>
</form>

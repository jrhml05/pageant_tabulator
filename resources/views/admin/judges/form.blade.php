{{-- Shared by create and edit. `$judge` is null when creating. --}}
@php
    $fields = [
        ['name', 'Name', 'text', 'name', null],
        ['email', 'Email', 'email', 'off', 'The judge signs in with this.'],
        ['password', 'Password', 'text', 'new-password', $judge
            ? 'Leave blank to keep the current password. 6 to 12 characters.'
            : '6 to 12 characters. It is shown here so you can hand it to the judge.'],
    ];
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
                    value="{{ $field === 'password' ? '' : old($field, $judge?->$field) }}" class="input mt-1.5"
                    @if ($help) aria-describedby="{{ $field }}-help" @endif
                    @error($field) aria-invalid="true" aria-errormessage="{{ $field }}-error" @enderror>
                @if ($help)
                    <p id="{{ $field }}-help" class="mt-1.5 text-sm text-ink-2">{{ $help }}</p>
                @endif
                @error($field)
                    <p id="{{ $field }}-error" class="field-error">{{ $message }}</p>
                @enderror
            </div>
        @endforeach
    </div>

    <div class="flex justify-end gap-2 border-t border-line px-5 py-4 sm:px-6">
        <a href="{{ route('judges.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">{{ $judge ? 'Save changes' : 'Add judge' }}</button>
    </div>
</form>

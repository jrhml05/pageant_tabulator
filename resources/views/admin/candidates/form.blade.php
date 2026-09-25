{{-- Shared by create and edit. `$candidate` is null when creating. --}}
@php
    $maxMb = rtrim(rtrim(number_format(\Illuminate\Http\UploadedFile::getMaxFilesize() / 1048576, 1), '0'), '.');
@endphp

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="card max-w-xl" novalidate>
    @csrf
    @if ($candidate)
        @method('PUT')
    @endif

    <div class="flex flex-col gap-5 p-5 sm:p-6">
        <div>
            @if ($candidate)
                <p class="label">Number</p>
                <p class="mt-1.5 text-lg font-semibold tabular-nums">No. {{ $candidate->id }}</p>
                <p class="mt-1.5 text-sm text-ink-2">Scores and the photo file are tied to the number, so it can't be changed. To renumber, delete this candidate and add them again.</p>
            @else
                <label for="number" class="label">Number</label>
                <input id="number" name="number" type="number" inputmode="numeric" min="1" max="999" required
                    value="{{ old('number', $data['next_number']) }}" class="input mt-1.5 max-w-32 tabular-nums"
                    aria-describedby="number-help"
                    @error('number') aria-invalid="true" aria-errormessage="number-error" @enderror>
                <p id="number-help" class="mt-1.5 text-sm text-ink-2">Judges and reports show the candidate by this number.</p>
                @error('number')
                    <p id="number-error" class="field-error">{{ $message }}</p>
                @enderror
            @endif
        </div>

        <div>
            <label for="department" class="label">Department <span class="font-normal text-ink-2">(optional)</span></label>
            <input id="department" name="department" type="text" autocomplete="off"
                value="{{ old('department', $candidate?->department) }}" class="input mt-1.5"
                @error('department') aria-invalid="true" aria-errormessage="department-error" @enderror>
            @error('department')
                <p id="department-error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="photo" class="label">{{ $candidate ? 'Replace photo' : 'Photo' }} <span class="font-normal text-ink-2">(optional)</span></label>
            @if ($candidate)
                <x-candidate-photo :division="$data['division']" :number="$candidate->id" class="mt-1.5 w-32 rounded-md border border-line" />
            @endif
            <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp"
                class="mt-1.5 block w-full text-sm text-ink-2 file:mr-3 file:min-h-10 file:cursor-pointer file:rounded-md file:border file:border-line-strong file:bg-surface file:px-3.5 file:text-sm file:font-medium file:text-ink hover:file:bg-surface-2"
                aria-describedby="photo-help"
                @error('photo') aria-invalid="true" aria-errormessage="photo-error" @enderror>
            <p id="photo-help" class="mt-1.5 text-sm text-ink-2">JPEG, PNG or WebP, up to {{ $maxMb }} MB. Portrait 4:5 fills the card without cropping.</p>
            @error('photo')
                <p id="photo-error" class="field-error">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex justify-end gap-2 border-t border-line px-5 py-4 sm:px-6">
        <a href="{{ route('candidates.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">{{ $candidate ? 'Save changes' : 'Add candidate' }}</button>
    </div>
</form>

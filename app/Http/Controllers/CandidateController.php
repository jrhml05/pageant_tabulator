<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CandidateController extends Controller
{
    // Field names as validation messages show them.
    private const ATTRIBUTES = ['school' => 'college/university'];

    public function index()
    {
        $data['title'] = 'Candidates';
        foreach (array_keys(config('pageant.divisions')) as $division) {
            $data[$division] = Candidate::division($division)->get();
        }

        return view('admin.candidates.index', compact('data'));
    }

    public function create(string $division)
    {
        $data['title'] = 'Add candidate';
        $data['division'] = $division;
        $data['label'] = config("pageant.divisions.{$division}");
        $data['next_number'] = (Candidate::where('division', $division)->max('number') ?? 0) + 1;

        return view('admin.candidates.create', compact('data'));
    }

    public function store(Request $request, string $division)
    {
        $label = config("pageant.divisions.{$division}");

        $validated = $request->validate([
            'number' => ['required', 'integer', 'min:1', 'max:999', Rule::unique('candidates')->where('division', $division)],
            'school' => 'nullable|string|max:255',
            'photo' => $this->photoRules(),
        ], [
            'number.unique' => "Another {$label} candidate already has this number.",
        ], self::ATTRIBUTES);

        $number = (int) $validated['number'];

        // Saved first so a photo that can't be converted stops the form before the candidate exists.
        if ($request->hasFile('photo')) {
            $this->savePhoto($division, $number, $request->file('photo'));
        }

        Candidate::create([
            'division' => $division,
            'number' => $number,
            'school' => $validated['school'] ?? null,
        ]);

        session()->flash('success', "{$label} candidate No. {$number} added. Judges see them on their next page load.");

        return redirect()->route('candidates.index');
    }

    public function edit(string $division, int $number)
    {
        $candidate = $this->find($division, $number);

        $data['title'] = 'Edit candidate';
        $data['division'] = $division;
        $data['label'] = config("pageant.divisions.{$division}");
        $data['candidate'] = $candidate;
        $data['scored'] = Score::where('candidate_id', $candidate->id)->whereNotNull('points')->exists();

        return view('admin.candidates.edit', compact('data'));
    }

    public function update(Request $request, string $division, int $number)
    {
        $candidate = $this->find($division, $number);

        $validated = $request->validate([
            'school' => 'nullable|string|max:255',
            'photo' => $this->photoRules(),
        ], [], self::ATTRIBUTES);

        $candidate->update(['school' => $validated['school'] ?? null]);

        if ($request->hasFile('photo')) {
            $this->savePhoto($division, $number, $request->file('photo'));
        }

        session()->flash('success', config("pageant.divisions.{$division}")." candidate No. {$number} saved.");

        return redirect()->route('candidates.index');
    }

    public function destroy(string $division, int $number)
    {
        // Their scores go with them (cascading foreign key).
        $this->find($division, $number)->delete();

        session()->flash('success', config("pageant.divisions.{$division}")." candidate No. {$number} and their scores were deleted.");

        return redirect()->route('candidates.index');
    }

    private function find(string $division, int $number): Candidate
    {
        return Candidate::where('division', $division)->where('number', $number)->firstOrFail();
    }

    private function photoRules(): array
    {
        return ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:'.(int) (UploadedFile::getMaxFilesize() / 1024)];
    }

    // Photos are looked up by number as img/{division}/{number}.jpg, so other formats are converted to JPEG.
    private function savePhoto(string $division, int $number, UploadedFile $photo): void
    {
        $dir = public_path("assets/img/{$division}");
        File::ensureDirectoryExists($dir);

        if ($photo->getMimeType() === 'image/jpeg') {
            $photo->move($dir, "{$number}.jpg");
        } else {
            $image = function_exists('imagecreatefromstring') ? @imagecreatefromstring($photo->get()) : false;
            if (! $image) {
                throw ValidationException::withMessages(['photo' => 'This image could not be converted to JPEG. Save it as a .jpg and upload that instead.']);
            }
            imagejpeg($image, "{$dir}/{$number}.jpg", 90);
            imagedestroy($image);
        }

        // Refresh the small copy the judge and admin pages load.
        Artisan::call('candidates:photos');
    }
}

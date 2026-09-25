<?php

namespace App\Http\Controllers;

use App\Models\Mr_candidate;
use App\Models\Ms_candidate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class CandidateController extends Controller
{
    private const MODELS = ['mr' => Mr_candidate::class, 'ms' => Ms_candidate::class];

    private const LABELS = ['mr' => 'Mr. LCUAA', 'ms' => 'Ms. LCUAA'];

    // Score sheets hold one row per candidate per judge, keyed here to their first criterion,
    // which is null until the judge scores. Final score rows are left out: the final score
    // seeder route creates them once the finalists are marked.
    private const SCORE_SHEETS = [
        'talent_scores' => 'mastery',
        'ravewear_scores' => 'style',
        'natlcost_scores' => 'design',
        'deptuni_scores' => 'presentation',
        'swimwear_scores' => 'body',
        'formalwear_scores' => 'beauty',
        'qna_scores' => 'relevance',
    ];

    // Per-judge stage totals, written as the judge fills in the sheets above.
    private const STAGE_TOTALS = ['prepageant_scores', 'prelim_scores'];

    private const RANK_COLUMNS = [
        'rave_wear', 'talent', 'prepageant', 'national_costume', 'dept_uniform',
        'swim_wear', 'formal_wear', 'qna', 'pageant', 'to_top_5', 'final',
    ];

    public function index()
    {
        $data['title'] = 'Candidates';
        $data['mr'] = Mr_candidate::orderBy('id')->get();
        $data['ms'] = Ms_candidate::orderBy('id')->get();

        return view('admin.candidates.index', compact('data'));
    }

    public function create(string $division)
    {
        $model = self::MODELS[$division];
        $data['title'] = 'Add candidate';
        $data['division'] = $division;
        $data['label'] = self::LABELS[$division];
        $data['next_number'] = ($model::max('id') ?? 0) + 1;

        return view('admin.candidates.create', compact('data'));
    }

    public function store(Request $request, string $division)
    {
        $model = self::MODELS[$division];

        $validated = $request->validate([
            'number' => "required|integer|min:1|max:999|unique:{$division}_candidates,id",
            'department' => 'nullable|string|max:255',
            'photo' => $this->photoRules(),
        ], [
            'number.unique' => 'Another '.self::LABELS[$division].' candidate already has this number.',
        ]);

        $number = (int) $validated['number'];

        // Saved first so a photo that can't be converted stops the form before the candidate exists.
        if ($request->hasFile('photo')) {
            $this->savePhoto($division, $number, $request->file('photo'));
        }

        DB::transaction(function () use ($model, $division, $number, $validated) {
            $model::insert([
                'id' => $number,
                'name' => (string) $number,
                'department' => $validated['department'] ?? null,
                // Once the top 5 are marked, only finalists are active, so a late addition is not one.
                'is_active' => $model::where('is_active', 0)->exists() ? 0 : 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->createScoreRows($division, $number);
        });

        session()->flash('success', self::LABELS[$division]." candidate No. {$number} added. Judges see them on their next page load.");

        return redirect()->route('candidates.index');
    }

    public function edit(string $division, int $number)
    {
        $data['title'] = 'Edit candidate';
        $data['division'] = $division;
        $data['label'] = self::LABELS[$division];
        $data['candidate'] = self::MODELS[$division]::findOrFail($number);
        $data['scored'] = $this->hasScores($division, $number);

        return view('admin.candidates.edit', compact('data'));
    }

    public function update(Request $request, string $division, int $number)
    {
        $candidate = self::MODELS[$division]::findOrFail($number);

        $validated = $request->validate([
            'department' => 'nullable|string|max:255',
            'photo' => $this->photoRules(),
        ]);

        $candidate->department = $validated['department'] ?? null;
        $candidate->save();

        if ($request->hasFile('photo')) {
            $this->savePhoto($division, $number, $request->file('photo'));
        }

        session()->flash('success', self::LABELS[$division]." candidate No. {$number} saved.");

        return redirect()->route('candidates.index');
    }

    public function destroy(string $division, int $number)
    {
        $candidate = self::MODELS[$division]::findOrFail($number);

        DB::transaction(function () use ($candidate, $division, $number) {
            foreach ([...array_keys(self::SCORE_SHEETS), ...self::STAGE_TOTALS, 'final_scores', 'rankings', 'final_ranks'] as $table) {
                DB::table("{$division}_{$table}")->where('candidate_id', $number)->delete();
            }
            $candidate->delete();
        });

        session()->flash('success', self::LABELS[$division]." candidate No. {$number} and their scores were deleted.");

        return redirect()->route('candidates.index');
    }

    private function createScoreRows(string $division, int $number): void
    {
        $now = now();
        // Same judges as the score seeders: score sheets for users 2 to 4, rankings for every judge.
        $judges = User::where('role', 'judge')->orderBy('id')->pluck('id');
        $scoringJudges = $judges->intersect(range(2, 4));

        foreach ([...array_keys(self::SCORE_SHEETS), ...self::STAGE_TOTALS] as $table) {
            DB::table("{$division}_{$table}")->insert($scoringJudges->map(fn ($judge) => [
                'candidate_id' => $number, 'judge_id' => $judge, 'created_at' => $now, 'updated_at' => $now,
            ])->values()->all());
        }

        $ranks = array_fill_keys(self::RANK_COLUMNS, 0);

        DB::table("{$division}_rankings")->insert($judges->map(fn ($judge) => [
            'candidate_id' => $number, 'judge_id' => $judge, ...$ranks, 'created_at' => $now, 'updated_at' => $now,
        ])->all());

        DB::table("{$division}_final_ranks")->insert([
            'candidate_id' => $number, ...$ranks, 'created_at' => $now, 'updated_at' => $now,
        ]);
    }

    private function hasScores(string $division, int $number): bool
    {
        foreach (self::SCORE_SHEETS as $table => $criterion) {
            if (DB::table("{$division}_{$table}")->where('candidate_id', $number)->whereNotNull($criterion)->exists()) {
                return true;
            }
        }

        return DB::table("{$division}_final_scores")->where('candidate_id', $number)->whereNotNull('wit')->exists();
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

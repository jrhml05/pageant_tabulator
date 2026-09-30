<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\User;
use App\Scoring\Announcement;
use App\Scoring\Segment;
use App\Scoring\Tabulator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ResultsController extends Controller
{
    // Long bond paper (8.5 x 13 in), landscape.
    private const PAPER = [0, 0, 612, 936];

    public function __construct(private Tabulator $tabulator) {}

    public function roundOne(string $division)
    {
        $this->rememberDivision($division);

        return view('admin.results.round1', $this->roundOneData($division));
    }

    public function roundOnePdf(string $division)
    {
        return Pdf::loadView('admin.results.pdf.round1', $this->roundOneData($division))
            ->setPaper(self::PAPER, 'landscape')
            ->stream("{$division}_round_1.pdf");
    }

    public function saveFinalists(Request $request, Announcement $announcement, string $division)
    {
        $validated = $request->validate([
            'finalists' => 'required|array|min:1',
            'finalists.*' => ['integer', Rule::exists('candidates', 'id')->where('division', $division)],
        ], [
            'finalists.required' => 'Tick at least one finalist.',
        ]);

        DB::transaction(function () use ($division, $validated) {
            Candidate::where('division', $division)->whereNotIn('id', $validated['finalists'])
                ->update(['is_finalist' => false, 'announce_order' => null]);
            Candidate::whereIn('id', $validated['finalists'])->update(['is_finalist' => true]);
        });

        // A new set of finalists gets a new call order; the same set keeps the one already printed.
        $announcement->shuffle($division);

        $count = count($validated['finalists']);
        $expected = (int) config('pageant.finalists');
        $label = config("pageant.divisions.{$division}");

        session()->flash('success', "{$count} {$label} finalists saved."
            .($count === $expected ? '' : " The usual number is {$expected}; change the ticks if that wasn't intended."));

        return redirect()->route('results.round1', $division);
    }

    public function shuffleAnnouncement(Announcement $announcement, string $division)
    {
        $announcement->shuffle($division, force: true);

        session()->flash('success', 'New call order drawn for the '.config("pageant.divisions.{$division}").' top 5. Print the announcement sheet again.');

        return redirect()->route('results.round1', $division);
    }

    /** Both divisions' finalists in their random call order, for the emcee. No ranks or scores. */
    public function announcementPdf()
    {
        $divisions = collect(config('pageant.divisions'))->map(fn ($label, $division) => Announcement::order($division));

        return Pdf::loadView('admin.results.pdf.announcement', ['divisions' => $divisions])
            ->setPaper(self::PAPER, 'landscape')
            ->stream('top_5_announcement.pdf');
    }

    public function segment(Request $request, string $division, string $segment)
    {
        $this->rememberDivision($division);

        return view('admin.results.segment', $this->segmentData($request, $division, $segment));
    }

    public function segmentPdf(Request $request, string $division, string $segment)
    {
        $data = $this->segmentData($request, $division, $segment);
        $name = $division.'_'.$segment.($data['judge'] ? "_judge{$data['seat']}" : '');

        return Pdf::loadView('admin.results.pdf.segment', $data)
            ->setPaper(self::PAPER, 'landscape')
            ->stream("{$name}.pdf");
    }

    /** The sidebar's results links open this division next. */
    private function rememberDivision(string $division): void
    {
        session()->put('results.division', $division);
    }

    private function roundOneData(string $division): array
    {
        return [
            'division' => $division,
            'label' => config("pageant.divisions.{$division}"),
            'result' => $this->tabulator->roundOne($division),
            'finalists' => Candidate::where('division', $division)->where('is_finalist', true)->pluck('id'),
            'announcement' => Announcement::order($division),
            'judges' => Segment::inRound(1)->map->panel->unique()
                ->flatMap(fn (string $panel) => User::onPanel($panel)->get()),
        ];
    }

    private function segmentData(Request $request, string $division, string $segment): array
    {
        $result = $this->tabulator->segment(Segment::findOrFail($segment), $division);

        $seat = $request->integer('judge') ?: null;
        $judge = $seat ? $result['judges']->get($seat - 1) : null;
        abort_if($seat && ! $judge, 404);

        return [
            'division' => $division,
            'label' => config("pageant.divisions.{$division}"),
            'result' => $result,
            'seat' => $seat,
            'judge' => $judge,
        ];
    }
}

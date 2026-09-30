<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\Score;
use App\Models\User;
use App\Scoring\Announcement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    /** Ms. finalists Nos. 8–12; Q&A totals rise with the number, so the ranking is 12, 11, 10, 9, 8. */
    private array $finalists;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('username', 'admin1')->first();

        $judge = User::where('username', 'judge1')->first();
        foreach (Candidate::division('ms')->get() as $candidate) {
            Score::create(['segment' => 'wit', 'candidate_id' => $candidate->id, 'judge_id' => $judge->id, 'criterion' => 'substance', 'points' => $candidate->number / 2]);
            Score::create(['segment' => 'wit', 'candidate_id' => $candidate->id, 'judge_id' => $judge->id, 'criterion' => 'delivery', 'points' => 0]);
        }
        $this->finalists = Candidate::division('ms')->where('number', '>=', 8)->pluck('id')->all();
    }

    public function test_saving_finalists_draws_a_call_order_that_is_neither_ranking_nor_numbers(): void
    {
        $this->saveFinalists($this->finalists);

        $order = $this->callOrder();
        $this->assertEqualsCanonicalizing([8, 9, 10, 11, 12], $order);
        $this->assertNotSame([8, 9, 10, 11, 12], $order);
        $this->assertNotSame([12, 11, 10, 9, 8], $order);
        $this->assertSame([1, 2, 3, 4, 5], Candidate::where('is_finalist', true)->orderBy('announce_order')->pluck('announce_order')->all());
    }

    public function test_every_reshuffle_avoids_the_ranking_the_numbers_and_the_previous_order(): void
    {
        $this->saveFinalists($this->finalists);
        $announcement = app(Announcement::class);

        for ($i = 0; $i < 40; $i++) {
            $before = $this->callOrder();
            $announcement->shuffle('ms', force: true);
            $after = $this->callOrder();

            $this->assertNotSame([8, 9, 10, 11, 12], $after);
            $this->assertNotSame([12, 11, 10, 9, 8], $after);
            $this->assertNotSame($before, $after);
        }
    }

    public function test_saving_the_same_finalists_again_keeps_the_printed_order(): void
    {
        $this->saveFinalists($this->finalists);
        $order = $this->callOrder();

        $this->saveFinalists(array_reverse($this->finalists));

        $this->assertSame($order, $this->callOrder());
    }

    public function test_changing_the_finalists_draws_a_new_order_and_clears_the_dropped_one(): void
    {
        $this->saveFinalists($this->finalists);
        $dropped = Candidate::find($this->finalists[0]);
        $added = Candidate::division('ms')->where('number', 7)->first();

        $this->saveFinalists([...array_slice($this->finalists, 1), $added->id]);

        $this->assertNull($dropped->fresh()->announce_order);
        $this->assertEqualsCanonicalizing([7, 9, 10, 11, 12], $this->callOrder());
    }

    public function test_reshuffle_button_and_announcement_sheet(): void
    {
        $this->saveFinalists($this->finalists);

        $this->actingAs($this->admin)->get('/results/ms/round-1')->assertOk()->assertSee('Announcement order');
        $this->actingAs($this->admin)->post('/results/ms/finalists/shuffle')->assertRedirect(route('results.round1', 'ms'));
        $this->actingAs($this->admin)->get('/announcement/print')->assertOk()->assertHeader('content-type', 'application/pdf');

        $html = view('admin.results.pdf.announcement', [
            'divisions' => collect(config('pageant.divisions'))->map(fn ($l, $d) => Announcement::order($d)),
        ])->render();

        $this->assertSame(1, preg_match_all('/No finalists saved yet/', $html), 'Mr. has no finalists yet');
        $this->assertStringNotContainsString('Rank', $html);
        preg_match_all('/No\. \d+<\/td>/', $html, $cells);
        $this->assertSame(array_map(fn ($n) => "No. {$n}</td>", $this->callOrder()), $cells[0]);
    }

    private function saveFinalists(array $ids): void
    {
        $this->actingAs($this->admin)->put('/results/ms/finalists', ['finalists' => $ids])->assertSessionHasNoErrors();
    }

    /** @return list<int> candidate numbers in call order */
    private function callOrder(): array
    {
        return Announcement::order('ms')->pluck('number')->all();
    }
}

{{-- How Round 1 is placed, as plain sentences. Shared by the results page and its PDF. --}}
@use('App\Scoring\Points')
Each segment's score is the average of its judges' totals, rounded to 2 decimals, at its weight in the round.
@foreach ($result['segments']->filter->isScaled() as $segment)
    {{ $segment->short }} is judged out of {{ Points::plain($segment->maxPoints()) }} and counts {{ Points::plain($segment->weight()) }}%.
@endforeach
The weighted scores add up to the total out of 100, compared before rounding, and the highest total ranks first.
A tie on the total goes to the higher average in {{ $result['tie_break']->map(fn ($key) => $result['segments'][$key]->short)->join(', then ') }};

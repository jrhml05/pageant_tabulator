@if ($value === null)–<span class="sr-only"> not scored</span>@elseif ($plain ?? false){{ App\Scoring\Points::plain($value / 100) }}@else{{ App\Scoring\Points::format($value) }}@endif

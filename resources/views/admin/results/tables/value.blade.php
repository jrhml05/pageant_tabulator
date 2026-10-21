@if ($value === null)–<span class="sr-only"> not scored</span>@else{{ App\Scoring\Points::format($value) }}@endif

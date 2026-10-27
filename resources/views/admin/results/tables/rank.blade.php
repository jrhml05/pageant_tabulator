{{ $row['rank'] ?? '' }}@if ($row['tied'])<span class="tie">Tie</span>@elseif ($row['decided'] ?? false)<span class="decided">Board of judges</span>@endif

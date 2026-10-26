{{-- Signature lines, four to a row. `$signers` is a list of labels; the board signs last on every printed sheet. --}}
@php
    $signers = [...$signers, config('pageant.board')];
@endphp
<table class="signatures">
    @foreach (array_chunk($signers, 4) as $row)
        <tr>
            @foreach ($row as $signer)
                <td style="width: 25%;"><div class="line">{{ $signer }}</div></td>
            @endforeach
            @for ($i = count($row); $i < 4; $i++)
                <td style="width: 25%;"></td>
            @endfor
        </tr>
    @endforeach
</table>

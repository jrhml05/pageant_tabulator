{{-- Signature lines, five to a row, so Round 1's ten signers take two rows. `$signers` is a list of labels; the board signs last on every printed sheet. --}}
@php
    $signers = [...$signers, config('pageant.board')];
@endphp
<table class="signatures">
    @foreach (array_chunk($signers, 5) as $row)
        <tr>
            @foreach ($row as $signer)
                <td style="width: 20%;"><div class="line">{{ $signer }}</div></td>
            @endforeach
            @for ($i = count($row); $i < 5; $i++)
                <td style="width: 20%;"></td>
            @endfor
        </tr>
    @endforeach
</table>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Tabulation - @yield('title')</title>

</head>
<style>
    .table {
    width: 100%;
    margin-bottom: 1rem;
    color: #858796
}

.table td,
.table th {
    padding: .75rem;
    vertical-align: top;
    border-top: 1px solid #e3e6f0
}

.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #e3e6f0
}

.table tbody+tbody {
    border-top: 2px solid #e3e6f0
}

.table-sm td,
.table-sm th {
    padding: .3rem
}

.table-bordered {
    border: 1px solid #e3e6f0
}

.table-bordered td,
.table-bordered th {
    border: 1px solid #e3e6f0
}

.table-bordered thead td,
.table-bordered thead th {
    border-bottom-width: 2px
}

.table-borderless tbody+tbody,
.table-borderless td,
.table-borderless th,
.table-borderless thead th {
    border: 0
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, .05)
}

.table-hover tbody tr:hover {
    color: #858796;
    background-color: rgba(0, 0, 0, .075)
}

.table-primary,
.table-primary>td,
.table-primary>th {
    background-color: #cdd8f6
}

.table-primary tbody+tbody,
.table-primary td,
.table-primary th,
.table-primary thead th {
    border-color: #a3b6ee
}

.table-hover .table-primary:hover {
    background-color: #b7c7f2
}

.table-hover .table-primary:hover>td,
.table-hover .table-primary:hover>th {
    background-color: #b7c7f2
}

.table-secondary,
.table-secondary>td,
.table-secondary>th {
    background-color: #dddde2
}

.table-secondary tbody+tbody,
.table-secondary td,
.table-secondary th,
.table-secondary thead th {
    border-color: #c0c1c8
}

.table-hover .table-secondary:hover {
    background-color: #cfcfd6
}

.table-hover .table-secondary:hover>td,
.table-hover .table-secondary:hover>th {
    background-color: #cfcfd6
}

.table-success,
.table-success>td,
.table-success>th {
    background-color: #bff0de
}

.table-success tbody+tbody,
.table-success td,
.table-success th,
.table-success thead th {
    border-color: #89e2c2
}

.table-hover .table-success:hover {
    background-color: #aaebd3
}

.table-hover .table-success:hover>td,
.table-hover .table-success:hover>th {
    background-color: #aaebd3
}

.table-info,
.table-info>td,
.table-info>th {
    background-color: #c7ebf1
}

.table-info tbody+tbody,
.table-info td,
.table-info th,
.table-info thead th {
    border-color: #96dbe4
}

.table-hover .table-info:hover {
    background-color: #b3e4ec
}

.table-hover .table-info:hover>td,
.table-hover .table-info:hover>th {
    background-color: #b3e4ec
}

.table-warning,
.table-warning>td,
.table-warning>th {
    background-color: #fceec9
}

.table-warning tbody+tbody,
.table-warning td,
.table-warning th,
.table-warning thead th {
    border-color: #fadf9b
}

.table-hover .table-warning:hover {
    background-color: #fbe6b1
}

.table-hover .table-warning:hover>td,
.table-hover .table-warning:hover>th {
    background-color: #fbe6b1
}

.table-danger,
.table-danger>td,
.table-danger>th {
    background-color: #f8ccc8
}

.table-danger tbody+tbody,
.table-danger td,
.table-danger th,
.table-danger thead th {
    border-color: #f3a199
}

.table-hover .table-danger:hover {
    background-color: #f5b7b1
}

.table-hover .table-danger:hover>td,
.table-hover .table-danger:hover>th {
    background-color: #f5b7b1
}

.table-light,
.table-light>td,
.table-light>th {
    background-color: #fdfdfe
}

.table-light tbody+tbody,
.table-light td,
.table-light th,
.table-light thead th {
    border-color: #fbfcfd
}

.table-hover .table-light:hover {
    background-color: #ececf6
}

.table-hover .table-light:hover>td,
.table-hover .table-light:hover>th {
    background-color: #ececf6
}

.table-dark,
.table-dark>td,
.table-dark>th {
    background-color: #d1d1d5
}

.table-dark tbody+tbody,
.table-dark td,
.table-dark th,
.table-dark thead th {
    border-color: #a9aab1
}

.table-hover .table-dark:hover {
    background-color: #c4c4c9
}

.table-hover .table-dark:hover>td,
.table-hover .table-dark:hover>th {
    background-color: #c4c4c9
}

.table-active,
.table-active>td,
.table-active>th {
    background-color: rgba(0, 0, 0, .075)
}

.table-hover .table-active:hover {
    background-color: rgba(0, 0, 0, .075)
}

.table-hover .table-active:hover>td,
.table-hover .table-active:hover>th {
    background-color: rgba(0, 0, 0, .075)
}

.table .thead-dark th {
    color: #fff;
    background-color: #5a5c69;
    border-color: #6c6e7e
}

.table .thead-light th {
    color: #6e707e;
    background-color: #eaecf4;
    border-color: #e3e6f0
}

.table-dark {
    color: #fff;
    background-color: #5a5c69
}

.table-dark td,
.table-dark th,
.table-dark thead th {
    border-color: #6c6e7e
}

.table-dark.table-bordered {
    border: 0
}

.table-dark.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(255, 255, 255, .05)
}

.table-dark.table-hover tbody tr:hover {
    color: #fff;
    background-color: rgba(255, 255, 255, .075)
}

@media (min-width:576px) {
    .d-sm-flex {
        display: flex !important
    }
}

.align-items-center {
    align-items: center !important
}

.justify-content-between {
    justify-content: space-between !important
}

.mb-4 {
    margin-bottom: 1.5rem !important
}

.h3 {
    font-size: 1.75rem
}

.h3 {
    margin-bottom: .5rem;
    font-weight: 400;
    line-height: 1.2
}

.mb-0 {
    margin-bottom: 0 !important
}

.text-gray-800 {
    color: #5a5c69 !important
}

</style>

<body id="page-top">

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">MS. UEP - PRELIMINARIES (JUDGE 5)</h1>
</div>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th style="width:10%">CANDIDATE #</th>
            <th style="width:15%">CASUAL WEAR 50%</th>
            <th style="width:15%">FORMAL WEAR 50%</th>
            <th style="width:10%">TOTAL 100%</th>
            <th style="width:10%">RANK</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data['candidate'] as $candidate)
            <tr>
                <td>{{ strtoupper($candidate->id) }}</td>
                @foreach ( $candidate->prelim_score as $score)

                    @if ($candidate->id == $score->candidate_id && $score->judge_id == 6)

                        <td>{{ $score->casual_wear }}</td>
                        <td>{{ $score->formal_wear }}</td>
                        <td>{{ $score->casual_wear + $score->formal_wear }}</td>

                        @forelse   ($data['rank'] as $rank)

                            @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 6)
                                <td>{{ $rank->prelim_rank }}</td>
                            @endif

                        @empty
                            <td></td>
                        @endforelse

                        {{-- <td></td> --}}

                    @endif

                @endforeach
                {{-- <td>{{ ROUND(($score_judge1 + $score_judge2 + $score_judge3) / 3, 2) }}</td> --}}
            </tr>
        @empty
            <tr>
                <td colspan="6">
                    <center>No Data Found</center>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
</body>
</html>
{{--
    Layout for every printed score sheet. Rendered by dompdf, which supports CSS 2.1 tables but not
    flexbox, grid or CSS variables, so this stays plain: ruled tables, Helvetica, one crimson rule.
    Sections: eyebrow, heading, content (the table), signatures (admin/results/pdf/signatures).
    The LCUAA mark heads every sheet on the left and the Mr. & Ms. LCUAA 2026 event logo on the right.
    Both are inlined so dompdf needs no file access or network.
--}}
@php
    $logo = 'data:image/png;base64,'.base64_encode(file_get_contents(public_path('assets/img/lcuaa-logo.png')));
    $eventLogo = 'data:image/jpeg;base64,'.base64_encode(file_get_contents(public_path('assets/img/event-art-print.jpg')));
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('heading') · Mr. & Ms. LCUAA 2026</title>
    <style>
        @page {
            margin: 34pt 40pt 46pt;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10pt;
            color: #1d1515;
        }

        .masthead {
            width: 100%;
            border-bottom: 2pt solid #a50000;
            padding-bottom: 8pt;
            margin-bottom: 14pt;
        }

        .masthead td {
            vertical-align: bottom;
            padding: 0;
        }

        .masthead td.logo {
            width: 30pt;
            padding-right: 10pt;
        }

        .masthead td.logo img {
            height: 40pt;
        }

        .event {
            font-size: 9pt;
            color: #5c4f50;
        }

        h1 {
            font-size: 17pt;
            margin: 2pt 0 0;
        }

        .masthead td.printed img {
            height: 46pt;
            margin-bottom: 3pt;
        }

        .printed {
            text-align: right;
            font-size: 8.5pt;
            color: #5c4f50;
        }

        table.sheet {
            width: 100%;
            border-collapse: collapse;
        }

        table.sheet thead {
            display: table-header-group;
        }

        table.sheet th {
            background: #f1ecec;
            font-size: 8.5pt;
            font-weight: bold;
            color: #3d3233;
            padding: 6pt 5pt;
            border: 0.75pt solid #a89c9d;
            text-align: center;
        }

        table.sheet td {
            padding: 5.5pt 5pt;
            border: 0.75pt solid #c4b9ba;
            text-align: center;
        }

        table.sheet tbody th {
            background: none;
            font-size: 10pt;
            color: #1d1515;
            border-color: #c4b9ba;
        }

        table.sheet th:first-child,
        table.sheet td:first-child {
            text-align: left;
            font-weight: bold;
        }

        table.sheet .sub {
            display: block;
            font-size: 7.5pt;
            font-weight: normal;
            color: #5c4f50;
        }

        table.sheet .strong {
            font-weight: bold;
        }

        .tie,
        .decided,
        .finalist {
            margin-left: 4pt;
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .tie {
            color: #b84a00;
        }

        .finalist {
            color: #8a0000;
        }

        .decided {
            color: #5c4f50;
        }

        .sr-only {
            display: none;
        }

        /* Announcement sheet: two division columns, large type for reading aloud. */
        table.columns {
            width: 100%;
            border-collapse: collapse;
        }

        td.column {
            width: 50%;
            vertical-align: top;
        }

        h2 {
            font-size: 13pt;
            margin: 0 0 6pt;
        }

        table.sheet.announce tbody td {
            font-size: 16pt;
            font-weight: bold;
            padding: 10pt 8pt;
        }

        table.sheet.announce tbody td.school {
            text-align: left;
            font-size: 12pt;
            font-weight: normal;
        }

        /* No column here is a rank, so none gets the rank highlight. */
        table.sheet.announce thead th:last-child {
            background: #f1ecec;
            color: #3d3233;
        }

        table.sheet.announce th.school {
            text-align: left;
        }

        .note {
            margin: 0 0 8pt;
            font-size: 9pt;
            color: #5c4f50;
        }

        table.sheet tbody tr:nth-child(even) td,
        table.sheet tbody tr:nth-child(even) th {
            background: #faf7f7;
        }

        /* The last column of a results sheet is the rank that gets read out. */
        table.sheet th:last-child {
            background: #fbe9e9;
            color: #8a0000;
        }

        table.sheet td:last-child {
            font-weight: bold;
            font-size: 11pt;
        }

        table.sheet td.empty {
            padding: 18pt;
            font-weight: normal;
            color: #5c4f50;
            text-align: center;
        }

        .signatures {
            width: 100%;
            margin-top: 34pt;
            page-break-inside: avoid;
        }

        .signatures td {
            padding: 0 14pt 0 0;
            vertical-align: top;
            font-size: 9pt;
            color: #5c4f50;
        }

        .signatures .line {
            border-top: 0.75pt solid #1d1515;
            padding-top: 4pt;
            margin-top: 28pt;
        }

        .footer {
            position: fixed;
            bottom: -26pt;
            left: 0;
            right: 0;
            font-size: 8pt;
            color: #5c4f50;
        }

        .footer .page:after {
            content: "Page " counter(page);
        }
    </style>
</head>

<body>
    <div class="footer">
        <table style="width: 100%;">
            <tr>
                <td>Mr. & Ms. LCUAA 2026 · @yield('heading')</td>
                <td style="text-align: right;"><span class="page"></span></td>
            </tr>
        </table>
    </div>

    <table class="masthead">
        <tr>
            <td class="logo"><img src="{{ $logo }}" alt="LCUAA"></td>
            <td>
                <div class="event">@yield('eyebrow')</div>
                <h1>@yield('heading')</h1>
            </td>
            <td class="printed">
                <img src="{{ $eventLogo }}" alt="Mr. & Ms. LCUAA 2026"><br>
                Printed {{ now()->format('j M Y, g:i A') }}
            </td>
        </tr>
    </table>

    @yield('content')

    @yield('signatures')
</body>

</html>

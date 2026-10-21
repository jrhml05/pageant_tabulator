{{--
    Layout for every printed score sheet. Rendered by dompdf, which supports CSS 2.1 tables but not
    flexbox, grid or CSS variables, so this stays plain: ruled tables, Helvetica, one teal rule.
    Sections: eyebrow, heading, content (the table), signatures (admin/results/pdf/signatures).
--}}
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
            color: #141920;
        }

        .masthead {
            width: 100%;
            border-bottom: 2pt solid #0f766e;
            padding-bottom: 8pt;
            margin-bottom: 14pt;
        }

        .masthead td {
            vertical-align: bottom;
            padding: 0;
        }

        .event {
            font-size: 9pt;
            color: #4a5360;
        }

        h1 {
            font-size: 17pt;
            margin: 2pt 0 0;
        }

        .printed {
            text-align: right;
            font-size: 8.5pt;
            color: #4a5360;
        }

        table.sheet {
            width: 100%;
            border-collapse: collapse;
        }

        table.sheet thead {
            display: table-header-group;
        }

        table.sheet th {
            background: #eceef1;
            font-size: 8.5pt;
            font-weight: bold;
            color: #333a44;
            padding: 6pt 5pt;
            border: 0.75pt solid #9aa3ad;
            text-align: center;
        }

        table.sheet td {
            padding: 5.5pt 5pt;
            border: 0.75pt solid #b4bcc7;
            text-align: center;
        }

        table.sheet tbody th {
            background: none;
            font-size: 10pt;
            color: #141920;
            border-color: #b4bcc7;
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
            color: #4a5360;
        }

        table.sheet .strong {
            font-weight: bold;
        }

        .tie,
        .finalist {
            margin-left: 4pt;
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .tie {
            color: #b42318;
        }

        .finalist {
            color: #0b5953;
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
            background: #eceef1;
            color: #333a44;
        }

        table.sheet.announce th.school {
            text-align: left;
        }

        .note {
            margin: 0 0 8pt;
            font-size: 9pt;
            color: #4a5360;
        }

        table.sheet tbody tr:nth-child(even) td,
        table.sheet tbody tr:nth-child(even) th {
            background: #f7f8fa;
        }

        /* The last column of a results sheet is the rank that gets read out. */
        table.sheet th:last-child {
            background: #dff1ee;
            color: #0b5953;
        }

        table.sheet td:last-child {
            font-weight: bold;
            font-size: 11pt;
        }

        table.sheet td.empty {
            padding: 18pt;
            font-weight: normal;
            color: #4a5360;
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
            color: #4a5360;
        }

        .signatures .line {
            border-top: 0.75pt solid #141920;
            padding-top: 4pt;
            margin-top: 28pt;
        }

        .footer {
            position: fixed;
            bottom: -26pt;
            left: 0;
            right: 0;
            font-size: 8pt;
            color: #4a5360;
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
            <td>
                <div class="event">@yield('eyebrow')</div>
                <h1>@yield('heading')</h1>
            </td>
            <td class="printed">Printed {{ now()->format('j M Y, g:i A') }}</td>
        </tr>
    </table>

    @yield('content')

    @yield('signatures')
</body>

</html>

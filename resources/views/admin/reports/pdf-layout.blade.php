{{--
    Layout for every printed score sheet. Rendered by dompdf, which supports CSS 2.1 tables but not
    flexbox, grid or CSS variables, so this stays plain: ruled tables, Helvetica, one teal rule.
    Sections: eyebrow (division, stage), heading, signatures ('judge' or 'panel'), content (the table).
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

        table.sheet th:first-child,
        table.sheet td:first-child {
            text-align: left;
            font-weight: bold;
        }

        table.sheet tbody tr:nth-child(even) td {
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

    <table class="signatures">
        <tr>
            @if (trim($__env->yieldContent('signatures')) === 'judge')
                <td style="width: 40%;"><div class="line">Signature of @yield('judge-label')</div></td>
                <td></td>
            @else
                @foreach (['Judge 1', 'Judge 2', 'Judge 3', 'Tabulator'] as $signer)
                    <td style="width: 25%;"><div class="line">{{ $signer }}</div></td>
                @endforeach
            @endif
        </tr>
    </table>
</body>

</html>

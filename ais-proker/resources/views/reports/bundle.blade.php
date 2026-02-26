<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Consolidated Report - AIS</title>
    <style>
        .page-break {
            page-break-after: always;
        }
        /* Ensure each part is isolated */
        .report-section {
            position: relative;
        }
    </style>
</head>
<body>
    @foreach($bundle as $module)
        <div class="report-section @if(!$loop->last) page-break @endif">
            {!! $module['content'] !!}
        </div>
    @endforeach
</body>
</html>

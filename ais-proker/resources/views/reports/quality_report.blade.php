<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Analisis Sasaran Mutu</title>
    <style>
        @page {
            margin: 1.5cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 11px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            color: #4f46e5;
            font-size: 22px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 12px;
        }
        .meta-info {
            width: 100%;
            margin-bottom: 20px;
        }
        .meta-info td {
            vertical-align: top;
        }
        .stats-container {
            width: 100%;
            margin-bottom: 25px;
        }
        .stats-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 15px;
            text-align: center;
            border-radius: 10px;
        }
        .stats-label {
            display: block;
            font-size: 9px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .stats-value {
            display: block;
            font-size: 18px;
            font-weight: bold;
            color: #1e293b;
        }
        .progress-section {
            background: #eef2ff;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 5px solid #4f46e5;
        }
        .progress-bar-container {
            width: 100%;
            height: 12px;
            background: #cbd5e1;
            border-radius: 6px;
            margin: 10px 0;
        }
        .progress-bar-fill {
            height: 100%;
            background: #4f46e5;
            border-radius: 6px;
        }
        table.main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table.main-table th {
            background: #4f46e5;
            color: white;
            text-align: left;
            padding: 10px;
            font-size: 9px;
            text-transform: uppercase;
        }
        table.main-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .achievement-progress {
            width: 120px;
        }
        .bar-mini {
            height: 6px;
            background: #e2e8f0;
            border-radius: 3px;
            width: 100%;
            overflow: hidden;
            margin-top: 4px;
        }
        .bar-fill {
            height: 100%;
            background: #4f46e5;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Analisis Sasaran Mutu</h1>
        <p>An Nahl Islamic School - Edukasi Terpadu Berwawasan Global</p>
    </div>

    <table class="meta-info">
        <tr>
            <td width="50%">
                <strong>Unit:</strong> {{ $unit->name ?? 'Semua Unit' }}<br>
                <strong>Tahun Ajaran:</strong> {{ $schoolYear->name }}
            </td>
            <td width="50%" style="text-align: right;">
                <strong>Tanggal Cetak:</strong> {{ $date }}<br>
                <strong>Jenis Laporan:</strong> Sasaran Mutu Operasional
            </td>
        </tr>
    </table>

    <div class="progress-section">
        <label style="font-weight: bold; color: #4f46e5;">QUALITY EFFECTIVENESS INDEX</label>
        <div class="progress-bar-container">
            <div class="progress-bar-fill" style="width: {{ $stats['effectiveness'] }}%"></div>
        </div>
        <table width="100%">
            <tr>
                <td style="font-size: 10px;">Indeks Efektivitas Mutu: <strong>{{ number_format($stats['effectiveness'], 1) }}%</strong></td>
                <td style="text-align: right; font-size: 10px;">Rata-rata Pencapaian: <strong>{{ number_format($stats['avg_achievement'], 1) }}%</strong></td>
            </tr>
        </table>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="40%">Sasaran Mutu</th>
                <th width="20%">Periode & Metode</th>
                <th width="15%">Target (%)</th>
                <th width="20%">Realisasi (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($targets as $index => $target)
                @php
                    $pct = $target->target > 0 ? ($target->achievement / $target->target) * 100 : 0;
                    $color = '#4f46e5';
                    if ($pct >= 100) $color = '#10b981';
                    elseif ($pct >= 75) $color = '#3b82f6';
                    elseif ($pct >= 50) $color = '#f59e0b';
                    else $color = '#ef4444';
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $target->sasaran }}</strong>
                    </td>
                    <td>
                        <span style="font-size: 9px; color: #4f46e5; font-weight: bold;">{{ $target->periode }}</span><br>
                        <span style="font-size: 8px; color: #666; font-style: italic;">{{ $target->metode }}</span>
                    </td>
                    <td style="text-align: center;">{{ number_format($target->target, 0) }}%</td>
                    <td>
                        <div style="font-weight: bold; font-size: 12px; color: {{ $color }};">
                            {{ number_format($target->achievement, 1) }}%
                        </div>
                        <div class="bar-mini">
                            <div class="bar-fill" style="width: {{ min($pct, 100) }}%; background-color: {{ $color }};"></div>
                        </div>
                        <span style="font-size: 8px; color: #94a3b8;">{{ number_format($pct, 0) }}% dari target</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated by AIS Program Kerja System | &copy; An Nahl Islamic School
    </div>
</body>
</html>

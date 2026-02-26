<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ikhtisar Kinerja Program Induk</title>
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
            padding: 12px;
            font-size: 9px;
            text-transform: uppercase;
        }
        table.main-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .no-col { text-align: center; width: 30px; }
        .count-col { text-align: center; width: 80px; }
        .progress-col { width: 150px; }
        
        .progress-mini-bar {
            width: 100%;
            height: 8px;
            background: #cbd5e1;
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-mini-fill {
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
        <h1>Ikhtisar Kinerja Program Induk</h1>
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
                <strong>Status Laporan:</strong> Ikhtisar Strategis
            </td>
        </tr>
    </table>

    <div class="progress-section">
        <label style="font-weight: bold; color: #4f46e5;">STRATEGIC PERFORMANCE SUMMARY</label>
        <div class="progress-bar-container">
            <div class="progress-bar-fill" style="width: {{ $stats['avg_progress'] }}%"></div>
        </div>
        <table width="100%">
            <tr>
                <td style="font-size: 10px;">Rata-rata Capaian Progres Unit: <strong>{{ $stats['avg_progress'] }}%</strong></td>
                <td style="text-align: right; font-size: 10px;">Total Program Induk: <strong>{{ $parentPrograms->count() }}</strong></td>
            </tr>
        </table>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th class="no-col">No</th>
                <th>Nama Program Induk</th>
                <th class="count-col">Jumlah Kegiatan</th>
                <th class="progress-col">Capaian Rata-rata</th>
            </tr>
        </thead>
        <tbody>
            @forelse($parentPrograms as $index => $parent)
                <tr>
                    <td class="no-col">{{ $index + 1 }}</td>
                    <td>
                        <strong style="font-size: 12px;">{{ $parent->name }}</strong><br>
                        <span style="color: #64748b; font-size: 9px; font-style: italic;">{{ $parent->description }}</span>
                    </td>
                    <td class="count-col">
                        <span style="font-weight: bold; font-size: 14px; color: #4f46e5;">{{ $parent->wp_count }}</span>
                    </td>
                    <td class="progress-col">
                        <div style="margin-bottom: 4px;">
                            <strong>{{ $parent->avg_progress }}%</strong>
                        </div>
                        <div class="progress-mini-bar">
                            @php
                                $color = '#4f46e5';
                                if($parent->avg_progress >= 100) $color = '#10b981';
                                elseif($parent->avg_progress >= 60) $color = '#3b82f6';
                                elseif($parent->avg_progress >= 30) $color = '#f59e0b';
                                else $color = '#ef4444';
                            @endphp
                            <div class="progress-mini-fill" style="width: {{ $parent->avg_progress }}%; background-color: {{ $color }};"></div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 40px; color: #94a3b8;">Tidak ada data program induk ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generated by AIS Program Kerja System | &copy; An Nahl Islamic School
    </div>
</body>
</html>

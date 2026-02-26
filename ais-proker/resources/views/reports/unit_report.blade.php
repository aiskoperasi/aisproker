<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Kinerja Program Kerja</title>
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
        .parent-row {
            background: #f1f5f9;
            font-weight: bold;
            color: #475569;
        }
        .status-badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-done { background: #dcfce7; color: #166534; }
        .status-progress { background: #dbeafe; color: #1e40af; }
        .status-planning { background: #fef3c7; color: #92400e; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        
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
        <h1>Laporan Detail Program Kerja</h1>
        <p>An Nahl Islamic School - Edukasi Terpadu Berwawasan Global</p>
    </div>

    <table class="meta-info">
        <tr>
            <td width="50%">
                <strong>Unit:</strong> {{ $unit->name ?? 'Semua Unit' }}<br>
                <strong>Tahun Ajaran:</strong> {{ $schoolYear->name }}<br>
                @if(request('parent_program_id'))
                    <strong>Filter Program:</strong> {{ $parentPrograms->first()->name ?? '-' }}
                @endif
            </td>
            <td width="50%" style="text-align: right;">
                <strong>Tanggal Cetak:</strong> {{ $date }}<br>
                <strong>Status Laporan:</strong> Eksekutif / Final
            </td>
        </tr>
    </table>

    <div class="progress-section">
        <label style="font-weight: bold; color: #4f46e5;">STRATEGIC PROGRESS OVERVIEW</label>
        <div class="progress-bar-container">
            <div class="progress-bar-fill" style="width: {{ $stats['avg_progress'] }}%"></div>
        </div>
        <table width="100%">
            <tr>
                <td style="font-size: 10px;">Rata-rata Capaian Progres: <strong>{{ $stats['avg_progress'] }}%</strong></td>
                <td style="text-align: right; font-size: 10px;">Tingkat Penyelesaian: <strong>{{ $stats['completion_rate'] }}%</strong></td>
            </tr>
        </table>
    </div>

    <table class="stats-container">
        <tr>
            <td width="25%">
                <div class="stats-box">
                    <span class="stats-label">Total Program</span>
                    <span class="stats-value">{{ $stats['total'] }}</span>
                </div>
            </td>
            <td width="25%">
                <div class="stats-box">
                    <span class="stats-label">Selesai</span>
                    <span class="stats-value">{{ $stats['completed'] }}</span>
                </div>
            </td>
            <td width="25%">
                <div class="stats-box">
                    <span class="stats-label">Total Anggaran</span>
                    <span class="stats-value">Rp {{ number_format($stats['budget'], 0, ',', '.') }}</span>
                </div>
            </td>
            <td width="25%">
                <div class="stats-box">
                    <span class="stats-label">Realisasi</span>
                    <span class="stats-value">Rp {{ number_format($stats['realization'], 0, ',', '.') }}</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th width="35%">Kegiatan / Program</th>
                <th width="15%">PJ</th>
                <th width="15%">Timeline</th>
                <th width="15%">Anggaran</th>
                <th width="10%">Progres</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($parentPrograms as $parent)
                <tr class="parent-row">
                    <td colspan="6">{{ $parent->name }}</td>
                </tr>
                @forelse($parent->workPrograms as $wp)
                    <tr>
                        <td>
                            <strong>{{ $wp->name }}</strong><br>
                            <span style="font-size: 8px; color: #666;">{{ $wp->description }}</span>
                        </td>
                        <td>{{ $wp->pj }}</td>
                        <td>{{ $wp->timeline }}</td>
                        <td>Rp {{ number_format($wp->budget, 0, ',', '.') }}</td>
                        <td style="text-align: center;">{{ $wp->progress }}%</td>
                        <td style="text-align: center;">
                            @php
                                $statusClass = 'status-planning';
                                if($wp->status == 'done') $statusClass = 'status-done';
                                elseif($wp->status == 'on_progress') $statusClass = 'status-progress';
                                elseif($wp->status == 'cancelled') $statusClass = 'status-cancelled';
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $wp->status }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #94a3b8; font-style: italic;">Belum ada kegiatan untuk kategori ini.</td>
                    </tr>
                @endforelse
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated by AIS Program Kerja System | &copy; An Nahl Islamic School
    </div>
</body>
</html>

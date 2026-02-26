<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Anggaran Pendapatan & Belanja Sekolah (APBS)</title>
    <style>
        @page {
            margin: 1.5cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.2;
            font-size: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #4f46e5;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 3px 0 0;
            color: #666;
            font-size: 10px;
        }
        .meta-info {
            width: 100%;
            margin-bottom: 15px;
        }
        .meta-info td {
            vertical-align: top;
        }
        .summary-grid {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .summary-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 10px;
            text-align: center;
            border-radius: 8px;
        }
        .summary-label {
            display: block;
            font-size: 8px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .summary-value {
            display: block;
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
        }
        .absorption-section {
            background: #f1f5f9;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #4f46e5;
        }
        .progress-bar-container {
            width: 100%;
            height: 10px;
            background: #cbd5e1;
            border-radius: 5px;
            margin: 8px 0;
        }
        .progress-bar-fill {
            height: 100%;
            background: #4f46e5;
            border-radius: 5px;
        }
        table.budget-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.budget-table th {
            background: #4f46e5;
            color: white;
            text-align: left;
            padding: 8px;
            font-size: 8px;
            text-transform: uppercase;
        }
        table.budget-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .amount-col { text-align: right; font-family: 'Courier', monospace; font-weight: bold; }
        .code-col { width: 60px; font-family: 'Courier', monospace; }
        .pct-col { text-align: center; width: 50px; }
        
        .total-row {
            background: #f8fafc;
            font-weight: bold;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Anggaran Pendapatan & Belanja Sekolah (APBS)</h1>
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
                <strong>Status Dokumen:</strong> Konfidensial / Keuangan
            </td>
        </tr>
    </table>

    <div class="absorption-section">
        <table width="100%">
            <tr>
                <td style="font-weight: bold; color: #4f46e5; font-size: 9px;">SUMMARY REALISASI ANGGARAN (KUMULATIF)</td>
                <td style="text-align: right; font-size: 11px;">Serapan: <strong>{{ $stats['absorption'] }}%</strong></td>
            </tr>
        </table>
        <div class="progress-bar-container">
            <div class="progress-bar-fill" style="width: {{ min($stats['absorption'], 100) }}%"></div>
        </div>
    </div>

    <table class="summary-grid">
        <tr>
            <td width="33%" style="padding-right: 5px;">
                <div class="summary-box">
                    <span class="summary-label">Total Pagu</span>
                    <span class="summary-value">Rp {{ number_format($stats['total_pagu'], 0, ',', '.') }}</span>
                </div>
            </td>
            <td width="33%" style="padding: 0 5px;">
                <div class="summary-box">
                    <span class="summary-label">Total Realisasi</span>
                    <span class="summary-value" style="color: #10b981;">Rp {{ number_format($stats['total_real'], 0, ',', '.') }}</span>
                </div>
            </td>
            <td width="33%" style="padding-left: 5px;">
                <div class="summary-box">
                    <span class="summary-label">Sisa Anggaran</span>
                    <span class="summary-value" style="color: #ef4444;">Rp {{ number_format($stats['sisa'], 0, ',', '.') }}</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="budget-table">
        <thead>
            <tr>
                <th class="code-col">Kode</th>
                <th>Keterangan Item Anggaran</th>
                <th width="100px" style="text-align: right;">Pagu (Rp)</th>
                <th width="100px" style="text-align: right;">Realisasi (Rp)</th>
                <th width="80px" style="text-align: right;">Sisa (Rp)</th>
                <th class="pct-col">%</th>
            </tr>
        </thead>
        <tbody>
            @foreach($budgets as $budget)
                @php
                    $pct = $budget->amount > 0 ? ($budget->realization / $budget->amount) * 100 : 0;
                    $sisa = $budget->amount - $budget->realization;
                @endphp
                <tr>
                    <td class="code-col">{{ $budget->code }}</td>
                    <td>
                        <strong>{{ $budget->description }}</strong><br>
                        <span style="font-size: 7px; color: #777;">{{ $budget->notes }}</span>
                    </td>
                    <td class="amount-col">{{ number_format($budget->amount, 0, ',', '.') }}</td>
                    <td class="amount-col" style="color: #059669;">{{ number_format($budget->realization, 0, ',', '.') }}</td>
                    <td class="amount-col" style="color: #dc2626;">{{ number_format($sisa, 0, ',', '.') }}</td>
                    <td class="pct-col">{{ number_format($pct, 1) }}%</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2">TOTAL KESELURUHAN</td>
                <td class="amount-col">{{ number_format($stats['total_pagu'], 0, ',', '.') }}</td>
                <td class="amount-col">{{ number_format($stats['total_real'], 0, ',', '.') }}</td>
                <td class="amount-col">{{ number_format($stats['sisa'], 0, ',', '.') }}</td>
                <td class="pct-col">{{ $stats['absorption'] }}%</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Generated by AIS Program Kerja System | &copy; An Nahl Islamic School
    </div>
</body>
</html>

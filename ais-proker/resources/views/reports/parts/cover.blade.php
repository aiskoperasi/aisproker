<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Cover Laporan</title>
    <style>
        @page { margin: 0; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            margin: 0; 
            padding: 0;
            color: #1e293b;
        }
        .container {
            width: 100%;
            height: 29.7cm; /* A4 Height */
            position: relative;
            background-color: #ffffff;
        }
        .top-accent {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 15px;
            background: linear-gradient(to right, #4f46e5, #06b6d4);
        }
        .logo-section {
            padding-top: 80px;
            text-align: center;
        }
        .logo {
            width: 120px;
            height: auto;
        }
        .title-section {
            margin-top: 100px;
            text-align: center;
            padding: 0 50px;
        }
        .main-title {
            font-size: 32px;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 20px;
        }
        .subtitle {
            font-size: 18px;
            color: #4f46e5;
            font-weight: bold;
            margin-bottom: 50px;
        }
        .divider {
            width: 80px;
            height: 4px;
            background-color: #4f46e5;
            margin: 0 auto 50px;
        }
        .info-section {
            margin-top: 100px;
            padding: 0 80px;
        }
        .info-table {
            width: 100%;
            font-size: 16px;
        }
        .info-label {
            color: #64748b;
            width: 150px;
            padding: 10px 0;
            text-transform: uppercase;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .info-value {
            font-weight: bold;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .footer-section {
            position: absolute;
            bottom: 80px;
            width: 100%;
            text-align: center;
        }
        .school-name {
            font-size: 18px;
            font-weight: bold;
            color: #1e293b;
        }
        .school-desc {
            font-size: 12px;
            color: #64748b;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-accent"></div>
        
        <div class="logo-section">
            <img src="{{ public_path('images/logo-annahl.png') }}" class="logo">
        </div>

        <div class="title-section">
            <h1 class="main-title">{{ $report_title }}</h1>
            @if($report_subtitle)
                <div class="subtitle">{{ $report_subtitle }}</div>
            @endif
            <div class="divider"></div>
        </div>

        <div class="info-section">
            <table class="info-table">
                <tr>
                    <td class="info-label">Unit Kerja</td>
                    <td class="info-value">{{ $unit->name ?? 'SELURUH UNIT' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Tahun Ajaran</td>
                    <td class="info-value">TA {{ $schoolYear->name }}</td>
                </tr>
                <tr>
                    <td class="info-label">Penyusun</td>
                    <td class="info-value">Ka Unit dan Tim</td>
                </tr>
                <tr>
                    <td class="info-label">Tanggal Cetak</td>
                    <td class="info-value">{{ date('d F Y') }}</td>
                </tr>
            </table>
        </div>

        <div class="footer-section">
            <div class="school-name">An Nahl Islamic School</div>
            <div class="school-desc">Jl. Raya Ciangsana KM7, Ciangsana Gunung Putri Bogor</div>
        </div>
    </div>
</body>
</html>

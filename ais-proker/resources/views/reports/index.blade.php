@extends('layouts.app')

@section('title', 'Pusat Laporan - AIS Program Kerja')
@section('page-title', 'Pusat Laporan')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Header Section --}}
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-theme-main mb-2">Penyusunan Laporan Terpadu</h2>
        <p class="text-theme-muted">Pilih modul laporan yang ingin digabungkan ke dalam satu dokumen PDF profesional lengkap dengan halaman cover.</p>
    </div>

    <form action="{{ route('reports.bundle') }}" method="POST" target="_blank">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Left Column: Selection --}}
            <div class="md:col-span-2 space-y-6">
                {{-- Modul Selection Card --}}
                <div class="bg-[var(--color-dark-card)] border border-theme rounded-2xl p-6 shadow-sm">
                    <h3 class="text-sm font-bold text-indigo-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fas fa-check-double"></i> Pilih Modul Laporan
                    </h3>
                    
                    <div class="space-y-4">
                        <label class="flex items-center p-4 border border-theme rounded-xl hover:bg-indigo-500/5 transition-all cursor-pointer group">
                            <input type="checkbox" name="modules[]" value="cover" checked 
                                   class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <div class="ml-4 flex-1">
                                <span class="block font-bold text-theme-main group-hover:text-indigo-500">Halaman Cover</span>
                                <span class="text-xs text-theme-muted">Menampilkan logo, judul, unit, dan tahun ajaran.</span>
                            </div>
                            <i class="fas fa-file-invoice text-gray-400 group-hover:text-indigo-500 transition-colors"></i>
                        </label>

                        <label class="flex items-center p-4 border border-theme rounded-xl hover:bg-indigo-500/5 transition-all cursor-pointer group">
                            <input type="checkbox" name="modules[]" value="quality"
                                   class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <div class="ml-4 flex-1">
                                <span class="block font-bold text-theme-main group-hover:text-indigo-500">Analisis Sasaran Mutu</span>
                                <span class="text-xs text-theme-muted">Laporan efektivitas mutu dan pencapaian target KPI.</span>
                            </div>
                            <i class="fas fa-chart-pie text-gray-400 group-hover:text-indigo-500 transition-colors"></i>
                        </label>

                        <label class="flex items-center p-4 border border-theme rounded-xl hover:bg-indigo-500/5 transition-all cursor-pointer group">
                            <input type="checkbox" name="modules[]" value="overview" checked
                                   class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <div class="ml-4 flex-1">
                                <span class="block font-bold text-theme-main group-hover:text-indigo-500">Ikhtisar Program</span>
                                <span class="text-xs text-theme-muted">Ringkasan kemajuan program kerja per kategori induk.</span>
                            </div>
                            <i class="fas fa-layer-group text-gray-400 group-hover:text-indigo-500 transition-colors"></i>
                        </label>

                        <label class="flex items-center p-4 border border-theme rounded-xl hover:bg-indigo-500/5 transition-all cursor-pointer group">
                            <input type="checkbox" name="modules[]" value="detailed"
                                   class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <div class="ml-4 flex-1">
                                <span class="block font-bold text-theme-main group-hover:text-indigo-500">Detail Laporan Proker</span>
                                <span class="text-xs text-theme-muted">Rincian aktivitas, anggaran, dan realisasi per program.</span>
                            </div>
                            <i class="fas fa-tasks text-gray-400 group-hover:text-indigo-500 transition-colors"></i>
                        </label>

                        <label class="flex items-center p-4 border border-theme rounded-xl hover:bg-indigo-500/5 transition-all cursor-pointer group">
                            <input type="checkbox" name="modules[]" value="budget"
                                   class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <div class="ml-4 flex-1">
                                <span class="block font-bold text-theme-main group-hover:text-indigo-500">Laporan Keuangan (APBS)</span>
                                <span class="text-xs text-theme-muted">Rekapitulasi pagu, realisasi, dan serapan anggaran.</span>
                            </div>
                            <i class="fas fa-file-invoice-dollar text-gray-400 group-hover:text-indigo-500 transition-colors"></i>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Right Column: Config --}}
            <div class="space-y-6">
                <div class="bg-[var(--color-dark-card)] border border-theme rounded-2xl p-6 shadow-sm sticky top-6">
                    <h3 class="text-sm font-bold text-indigo-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fas fa-cog"></i> Pengaturan
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-theme-muted uppercase mb-2">Judul Laporan</label>
                            <input type="text" name="report_title" value="LAPORAN PROGRAM KERJA & ANGGARAN" 
                                   class="w-full bg-[var(--color-dark-bg)] border border-theme rounded-xl px-4 py-2 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-theme-muted uppercase mb-2">Sub Judul (Optional)</label>
                            <input type="text" name="report_subtitle" placeholder="Misal: Semester 1 / Laporan Akhir" 
                                   class="w-full bg-[var(--color-dark-bg)] border border-theme rounded-xl px-4 py-2 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>

                        <div class="pt-4 border-t border-theme">
                            <div class="flex flex-col gap-2">
                                <div class="flex justify-between text-xs text-theme-muted">
                                    <span>Unit:</span>
                                    <span class="font-bold">{{ $unit->name ?? 'Selua Unit' }}</span>
                                </div>
                                <div class="flex justify-between text-xs text-theme-muted">
                                    <span>Tahun Ajaran:</span>
                                    <span class="font-bold">{{ $schoolYear->name }}</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-indigo-500/20 transition-all flex items-center justify-center gap-2 mt-4">
                            <i class="fas fa-file-pdf"></i> Generate Bundled PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

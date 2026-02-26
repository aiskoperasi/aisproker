@extends('layouts.app')

@section('title', 'Ikhtisar Program Induk')
@section('page-title', 'Ikhtisar Program Induk')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="glass p-8 rounded-3xl border border-white/10 shadow-2xl overflow-hidden relative">
        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/10 blur-3xl -mr-32 -mt-32 rounded-full"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <h2 class="text-3xl font-medium text-theme-main tracking-tight">Kinerja Program Induk</h2>
                <p class="text-theme-muted max-w-2xl font-medium">Ringkasan performa strategis berdasarkan pengelompokan program kerja utama di seluruh unit.</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('export.report', ['type' => 'overview']) }}" target="_blank" class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold px-5 py-3 rounded-2xl transition-all shadow-lg shadow-emerald-500/25 flex items-center gap-2">
                    <i class="fas fa-file-pdf"></i> Export PDF Report
                </a>
                <div class="px-6 py-3 bg-indigo-500/10 rounded-2xl border border-indigo-500/20 text-center">
                    <p class="text-[10px] font-medium text-indigo-500 uppercase tracking-widest mb-1">Total Induk</p>
                    <p class="text-2xl font-medium text-theme-main">{{ $mainPrograms->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Table --}}
    <div class="glass rounded-3xl border border-white/10 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-gray-300 dark:border-gray-700 bg-gray-50/50 dark:bg-white/5 uppercase tracking-widest text-[10px] font-medium text-theme-muted">
                        <th class="px-6 py-5 text-center w-16">No</th>
                        <th class="px-6 py-5">Nama Program Induk</th>
                        <th class="px-6 py-5">Deskripsi Singkat</th>
                        <th class="px-6 py-5 text-center">Jumlah Kegiatan</th>
                        <th class="px-6 py-5 w-64">Capaian Rata-rata</th>
                        <th class="px-6 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
                    @forelse($mainPrograms as $index => $item)
                        @php
                            $progress = round($item->average_progress);
                            $color = 'indigo';
                            if ($progress >= 100) $color = 'emerald';
                            elseif ($progress >= 60) $color = 'blue';
                            elseif ($progress >= 30) $color = 'amber';
                            else $color = 'red';

                            $colorClasses = [
                                'emerald' => 'bg-emerald-500 shadow-emerald-500/20',
                                'blue'    => 'bg-blue-500 shadow-blue-500/20',
                                'indigo'  => 'bg-indigo-500 shadow-indigo-500/20',
                                'amber'   => 'bg-amber-500 shadow-amber-500/20',
                                'red'     => 'bg-red-500 shadow-red-500/20',
                            ];
                            $barClass = $colorClasses[$color] ?? $colorClasses['indigo'];
                        @endphp
                        <tr class="group hover:bg-indigo-500/[0.02] transition-colors duration-300">
                            <td class="px-6 py-6 font-semibold text-theme-muted">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-6">
                                <div class="font-semibold text-theme-main text-base group-hover:text-indigo-500 transition-colors tracking-tight">
                                    {{ $item->name ?: 'Lainnya / Tidak Terkategori' }}
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <p class="text-theme-muted line-clamp-2 italic text-xs leading-relaxed">
                                    {{ $item->description ?: 'Tidak ada deskripsi tambahan.' }}
                                </p>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-500 font-bold border border-indigo-500/20">
                                    {{ $item->sub_count }}
                                </span>
                            </td>
                            <td class="px-6 py-6">
                                <div class="space-y-2">
                                    <div class="flex justify-between text-[10px] font-bold uppercase tracking-widest text-theme-muted">
                                        <span>Progress</span>
                                        <span class="text-theme-main">{{ $progress }}%</span>
                                    </div>
                                    <div class="w-full h-2 bg-gray-200 dark:bg-white/5 rounded-full overflow-hidden shadow-inner flex">
                                        <div class="h-full {{ $barClass }} rounded-full transition-all duration-1000 group-hover:brightness-110 shadow-lg"
                                             style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <a href="{{ route('work-programs.index', ['parent_program_id' => $item->id]) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-500/10 hover:bg-indigo-500 text-indigo-500 hover:text-white text-xs font-bold transition-all duration-300 border border-indigo-500/20 group-hover:scale-105">
                                    <i class="fas fa-arrow-right"></i> Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4 opacity-30">
                                    <i class="fas fa-folder-open text-6xl"></i>
                                    <p class="text-xl font-bold">Data program tidak ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

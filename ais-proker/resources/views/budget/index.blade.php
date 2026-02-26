@extends('layouts.app')

@section('title', 'APBS — Anggaran Pendapatan & Belanja Sekolah')
@section('page-title', 'APBS')

@section('content')
@php
    $totalPagu     = $budgets->sum('amount');
    $totalReal     = $budgets->sum('realization');
    $totalSisa     = $totalPagu - $totalReal;
    $absorption    = $totalPagu > 0 ? round(($totalReal / $totalPagu) * 100) : 0;
    $absColor      = $absorption > 90 ? 'rose' : ($absorption > 70 ? 'amber' : 'emerald');
@endphp

<div class="space-y-6">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- STAT CARDS                                                   --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="glass rounded-2xl border border-white/10 p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-indigo-500/15 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-coins text-indigo-400 text-lg"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] text-theme-muted uppercase tracking-wider font-bold">Total Pagu</p>
                <p class="text-lg font-black text-indigo-400 truncate">Rp {{ number_format($totalPagu, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="glass rounded-2xl border border-white/10 p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-emerald-500/15 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check-circle text-emerald-400 text-lg"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] text-theme-muted uppercase tracking-wider font-bold">Total Realisasi</p>
                <p class="text-lg font-black text-emerald-400 truncate">Rp {{ number_format($totalReal, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="glass rounded-2xl border border-white/10 p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-rose-500/15 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-wallet text-rose-400 text-lg"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] text-theme-muted uppercase tracking-wider font-bold">Sisa Anggaran</p>
                <p class="text-lg font-black text-rose-400 truncate">Rp {{ number_format($totalSisa, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="glass rounded-2xl border border-white/10 p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-{{ $absColor }}-500/15 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-gauge-high text-{{ $absColor }}-400 text-lg"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] text-theme-muted uppercase tracking-wider font-bold">Serapan</p>
                <p class="text-lg font-black text-{{ $absColor }}-400">{{ $absorption }}%</p>
            </div>
        </div>
    </div>

    {{-- Overall Absorption Bar --}}
    <div class="glass rounded-2xl border border-white/10 p-5">
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-semibold text-theme-muted">Serapan Keseluruhan</span>
            <span class="text-sm font-black text-{{ $absColor }}-400">{{ $absorption }}%</span>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
            <div class="h-3 rounded-full bg-gradient-to-r
                @if($absorption > 90) from-rose-400 to-rose-600
                @elseif($absorption > 70) from-amber-400 to-amber-500
                @else from-emerald-400 to-emerald-600 @endif
                transition-all duration-1000" style="width: {{ min($absorption, 100) }}%"></div>
        </div>
        <div class="flex justify-between text-xs text-theme-muted mt-2">
            <span>{{ $budgets->count() }} item anggaran</span>
            <span>Sisa: <strong class="text-rose-400">Rp {{ number_format($totalSisa, 0, ',', '.') }}</strong></span>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- MAIN CARD: Table + Cards                                     --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="glass rounded-2xl border border-white/10 shadow-xl p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <h2 class="text-xl font-bold text-indigo-400 flex items-center gap-2">
                <i class="fas fa-file-invoice-dollar"></i> Rincian APBS
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('budget.create') }}"
                   class="bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-bold px-4 py-2 rounded-xl transition-all shadow-lg shadow-indigo-500/25 flex items-center gap-2">
                    <i class="fas fa-plus"></i> Tambah Item
                </a>
                <a href="{{ route('export.budget') }}" target="_blank"
                   class="bg-emerald-500/10 hover:bg-emerald-500 text-emerald-500 hover:text-white text-sm font-bold px-4 py-2 rounded-xl transition-all border border-emerald-500/20 flex items-center gap-2">
                    <i class="fas fa-file-pdf"></i> <span class="hidden sm:inline">Export PDF</span> APBS
                </a>
            </div>
        </div>

        {{-- ===== MOBILE CARD VIEW ===== --}}
        <div class="md:hidden space-y-3">
            @if($budgets->count() > 0)
                @foreach($budgets as $budget)
                @php
                    $pct      = $budget->amount > 0 ? ($budget->realization / $budget->amount) * 100 : 0;
                    $barColor = $pct > 90 ? 'bg-rose-500' : ($pct > 70 ? 'bg-amber-500' : 'bg-emerald-500');
                    $txtColor = $pct > 90 ? 'text-rose-400' : ($pct > 70 ? 'text-amber-400' : 'text-emerald-400');
                @endphp
                <div class="bg-white/50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl p-4 space-y-3">
                    {{-- Header: Kode + Serapan Badge --}}
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <span class="text-[11px] font-black font-mono text-indigo-400 bg-indigo-500/10 px-2 py-0.5 rounded-lg">{{ $budget->code }}</span>
                            <p class="font-bold text-theme-main text-sm mt-1.5 leading-snug">{{ $budget->description }}</p>
                            @if($budget->notes)
                            <p class="text-[10px] text-theme-muted italic mt-0.5">{{ $budget->notes }}</p>
                            @endif
                        </div>
                        <span class="flex-shrink-0 text-xs font-black {{ $txtColor }}">{{ number_format($pct, 1) }}%</span>
                    </div>

                    {{-- Serapan Bar --}}
                    <div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                            <div class="{{ $barColor }} h-2 rounded-full transition-all duration-700"
                                 style="width: {{ min($pct, 100) }}%"></div>
                        </div>
                    </div>

                    {{-- Amounts --}}
                    <div class="grid grid-cols-2 gap-3 pt-1">
                        <div>
                            <p class="text-[9px] text-theme-muted uppercase font-bold tracking-wider">Pagu</p>
                            <p class="text-xs font-black font-mono text-theme-main">Rp {{ number_format($budget->amount, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] text-theme-muted uppercase font-bold tracking-wider">Realisasi</p>
                            <p class="text-xs font-black font-mono text-emerald-400">Rp {{ number_format($budget->realization, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-[9px] text-theme-muted uppercase font-bold tracking-wider">Sisa</p>
                            <p class="text-xs font-black font-mono text-rose-400">Rp {{ number_format($budget->amount - $budget->realization, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- Action --}}
                    <a href="{{ route('budget.edit', $budget->id) }}"
                       class="w-full flex items-center justify-center gap-2 bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 py-2 rounded-xl text-xs font-semibold transition-all">
                        <i class="fas fa-edit"></i> Edit Item
                    </a>
                </div>
                @endforeach
            @else
                <div class="py-12 text-center text-theme-muted italic">
                    <i class="fas fa-inbox text-4xl mb-3 block opacity-30"></i>
                    Belum ada data anggaran.
                </div>
            @endif
        </div>

        {{-- ===== DESKTOP TABLE VIEW ===== --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700 text-xs uppercase tracking-wider text-theme-muted">
                        <th class="px-4 py-3 w-28">Kode</th>
                        <th class="px-4 py-3">Keterangan / Item Anggaran</th>
                        <th class="px-4 py-3 text-right">Pagu (Rp)</th>
                        <th class="px-4 py-3 text-right">Realisasi (Rp)</th>
                        <th class="px-4 py-3 text-right">Sisa (Rp)</th>
                        <th class="px-4 py-3 text-center w-32">Serapan</th>
                        <th class="px-4 py-3 text-center w-20">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
                    @if($budgets->count() > 0)
                        @foreach($budgets as $budget)
                        @php
                            $pct      = $budget->amount > 0 ? ($budget->realization / $budget->amount) * 100 : 0;
                            $barColor = $pct > 90 ? 'bg-rose-500' : ($pct > 70 ? 'bg-amber-500' : 'bg-emerald-500');
                            $txtColor = $pct > 90 ? 'text-rose-400' : ($pct > 70 ? 'text-amber-400' : 'text-emerald-400');
                        @endphp
                        <tr class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors">
                            <td class="px-4 py-4">
                                <span class="font-mono font-black text-indigo-400 text-xs bg-indigo-500/10 px-2 py-1 rounded-lg">{{ $budget->code }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-semibold text-theme-main">{{ $budget->description }}</div>
                                @if($budget->notes)
                                <div class="text-[10px] text-theme-muted italic mt-0.5">{{ $budget->notes }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right font-mono font-bold text-theme-main text-sm">
                                {{ number_format($budget->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-right font-mono font-bold text-emerald-400 text-sm">
                                {{ number_format($budget->realization, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-right font-mono font-bold text-rose-400 text-sm">
                                {{ number_format($budget->amount - $budget->realization, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4">
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-1 overflow-hidden">
                                    <div class="{{ $barColor }} h-2 rounded-full transition-all duration-700"
                                         style="width: {{ min($pct, 100) }}%"></div>
                                </div>
                                <div class="text-[10px] text-center font-bold {{ $txtColor }}">{{ number_format($pct, 1) }}%</div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <a href="{{ route('budget.edit', $budget->id) }}"
                                   class="inline-flex items-center gap-1 bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach

                        {{-- Totals Row --}}
                        <tr class="bg-indigo-500/5 border-t-2 border-indigo-500/20 font-bold">
                            <td class="px-4 py-3" colspan="2">
                                <span class="text-xs uppercase tracking-wider text-indigo-400 font-black">Total</span>
                            </td>
                            <td class="px-4 py-3 text-right font-mono font-black text-theme-main">
                                {{ number_format($totalPagu, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-right font-mono font-black text-emerald-400">
                                {{ number_format($totalReal, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-right font-mono font-black text-rose-400">
                                {{ number_format($totalSisa, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-xs font-black text-{{ $absColor }}-400">{{ $absorption }}%</span>
                            </td>
                            <td></td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="7" class="py-16 text-center text-theme-muted italic">
                                <i class="fas fa-inbox text-4xl mb-3 block opacity-30"></i>
                                Belum ada data anggaran.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

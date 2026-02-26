@extends('layouts.app')

@section('title', 'Dashboard — AIS Program Kerja')
@section('page-title', 'Dashboard')

@section('content')
@php
    $now = \Carbon\Carbon::now()->locale('id');
    $tahun = $now->year;
@endphp

<div class="space-y-6">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- HERO: Greeting + Date                                       --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="glass rounded-2xl border border-white/10 p-6 bg-gradient-to-r from-indigo-500/10 to-purple-500/10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-black text-theme-main">
                    Dashboard {{ session('unit_name') ?? 'Global' }}
                </h1>
                <p class="text-theme-muted text-sm mt-1">
                    Selamat datang di <span class="text-indigo-400 font-bold">AIS Program Kerja</span>
                </p>
                    <i class="fas fa-calendar-alt mr-1 text-indigo-400"></i>
                    {{ $now->isoFormat('dddd, D MMMM Y') }} &nbsp;·&nbsp; 
                    <span class="font-bold text-theme-main">TA {{ $activeSchoolYear->name ?? $tahun }}</span>
                </p>
                @if($visi)
                <p class="text-xs text-indigo-300 mt-2 italic max-w-xl line-clamp-2">
                    <i class="fas fa-quote-left mr-1 opacity-40"></i>{{ $visi->content }}
                </p>
                @endif
            </div>
            <div class="flex gap-3 flex-shrink-0">
                <a href="{{ route('work-programs.index') }}"
                   class="bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-bold px-4 py-2.5 rounded-xl transition-all shadow-lg shadow-indigo-500/25 flex items-center gap-2">
                    <i class="fas fa-list-check"></i> Program Kerja
                </a>
                <a href="{{ route('strategic.index') }}"
                   class="bg-white/10 hover:bg-white/20 text-theme-main text-sm font-bold px-4 py-2.5 rounded-xl transition-all flex items-center gap-2">
                    <i class="fas fa-bullseye"></i> Strategis
                </a>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- ROW 1: Key Stats                                            --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total Program --}}
        <div class="glass rounded-2xl border border-white/10 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-500/15 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-diagram-project text-indigo-400 text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-theme-muted uppercase tracking-wider font-bold">Total Program</p>
                <p class="text-3xl font-black text-indigo-400">{{ $totalPrograms }}</p>
            </div>
        </div>

        {{-- Avg Progress --}}
        <div class="glass rounded-2xl border border-white/10 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-500/15 flex items-center justify-center flex-shrink-0">
                @php
                    $pc = $avgProgress >= 100 ? 'emerald' : ($avgProgress >= 60 ? 'blue' : ($avgProgress >= 30 ? 'amber' : 'red'));
                @endphp
                <i class="fas fa-gauge-high text-{{ $pc }}-400 text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-theme-muted uppercase tracking-wider font-bold">Rata-rata Progress</p>
                <p class="text-3xl font-black text-{{ $pc }}-400">{{ $avgProgress }}%</p>
            </div>
        </div>

        {{-- Selesai --}}
        <div class="glass rounded-2xl border border-white/10 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/15 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-circle-check text-emerald-400 text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-theme-muted uppercase tracking-wider font-bold">Selesai</p>
                <p class="text-3xl font-black text-emerald-400">{{ $donePrograms }}</p>
            </div>
        </div>

        {{-- Serapan Anggaran --}}
        <div class="glass rounded-2xl border border-white/10 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-500/15 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-coins text-amber-400 text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-theme-muted uppercase tracking-wider font-bold">Serapan Anggaran</p>
                <p class="text-3xl font-black text-amber-400">{{ $budgetAbsorption }}%</p>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- ROW 2: Progress Ring + Status Donut + Budget Bar            --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Overall Progress Ring --}}
        @php
            $radius = 50;
            $circ   = 2 * M_PI * $radius;
            $dash   = $circ - ($avgProgress / 100 * $circ);
            $ringColor = $avgProgress >= 100 ? '#10b981' : ($avgProgress >= 60 ? '#3b82f6' : ($avgProgress >= 30 ? '#f59e0b' : '#ef4444'));
        @endphp
        <div class="glass rounded-2xl border border-white/10 p-6 flex flex-col items-center justify-center text-center">
            <p class="text-xs text-theme-muted uppercase tracking-wider font-bold mb-4">Progress Keseluruhan</p>
            <div class="relative w-36 h-36">
                <svg class="w-full h-full -rotate-90" viewBox="0 0 120 120">
                    <circle cx="60" cy="60" r="{{ $radius }}" fill="none"
                            stroke="currentColor" class="text-gray-200 dark:text-gray-700" stroke-width="12"/>
                    <circle cx="60" cy="60" r="{{ $radius }}" fill="none"
                            stroke="{{ $ringColor }}" stroke-width="12"
                            stroke-linecap="round"
                            stroke-dasharray="{{ number_format($circ, 2) }}"
                            stroke-dashoffset="{{ number_format($dash, 2) }}"
                            style="transition: stroke-dashoffset 1.2s ease;"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-3xl font-black" style="color: {{ $ringColor }}">{{ $avgProgress }}%</span>
                    <span class="text-[10px] text-theme-muted mt-0.5">Program Kerja</span>
                </div>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-2 w-full text-xs">
                <div class="bg-blue-500/10 text-blue-400 rounded-lg py-1.5 text-center font-bold">
                    {{ $ongoingPrograms }} On Progress
                </div>
                <div class="bg-amber-500/10 text-amber-400 rounded-lg py-1.5 text-center font-bold">
                    {{ $planningPrograms }} Planning
                </div>
            </div>
        </div>

        {{-- Budget Absorption --}}
        <div class="glass rounded-2xl border border-white/10 p-6">
            <p class="text-xs text-theme-muted uppercase tracking-wider font-bold mb-5">Anggaran Program Kerja</p>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-xs mb-1.5">
                        <span class="text-theme-muted">Total Anggaran</span>
                        <span class="font-bold text-theme-main">Rp {{ number_format($totalWorkBudget, 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="h-2.5 rounded-full bg-indigo-500" style="width: 100%"></div>
                    </div>
                </div>
                <div>
                    @php $absorp = $totalWorkBudget > 0 ? round(($totalWorkReal / $totalWorkBudget) * 100) : 0; @endphp
                    <div class="flex justify-between text-xs mb-1.5">
                        <span class="text-theme-muted">Realisasi</span>
                        <span class="font-bold text-emerald-400">Rp {{ number_format($totalWorkReal, 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="h-2.5 rounded-full bg-emerald-500 transition-all duration-1000"
                             style="width: {{ $absorp }}%"></div>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-theme-muted">Sisa Anggaran</span>
                        <span class="text-sm font-black text-amber-400">
                            Rp {{ number_format($totalWorkBudget - $totalWorkReal, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="mt-2 flex items-center gap-2">
                        <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full bg-gradient-to-r from-emerald-400 to-emerald-600"
                                 style="width: {{ $absorp }}%"></div>
                        </div>
                        <span class="text-xs font-black text-emerald-400">{{ $absorp }}%</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Distribution --}}
        <div class="glass rounded-2xl border border-white/10 p-6">
            <p class="text-xs text-theme-muted uppercase tracking-wider font-bold mb-5">Distribusi Status</p>
            @php
                $statuses = [
                    ['label' => 'Selesai',     'value' => $statusChart['done'],        'color' => 'bg-emerald-500', 'text' => 'text-emerald-400'],
                    ['label' => 'On Progress', 'value' => $statusChart['on_progress'], 'color' => 'bg-blue-500',    'text' => 'text-blue-400'],
                    ['label' => 'Planning',    'value' => $statusChart['planning'],    'color' => 'bg-amber-500',   'text' => 'text-amber-400'],
                    ['label' => 'Dibatalkan',  'value' => $statusChart['cancelled'],   'color' => 'bg-red-500',     'text' => 'text-red-400'],
                ];
            @endphp
            <div class="space-y-3">
                @foreach($statuses as $s)
                @php $pct = $totalPrograms > 0 ? round(($s['value'] / $totalPrograms) * 100) : 0; @endphp
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="font-semibold text-theme-muted">{{ $s['label'] }}</span>
                        <span class="{{ $s['text'] }} font-bold">{{ $s['value'] }} program ({{ $pct }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $s['color'] }} transition-all duration-700"
                             style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- ROW 3: Program Highlight + Recent Activity                  --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Top Progressing Programs --}}
        <div class="lg:col-span-2 glass rounded-2xl border border-white/10 p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-base font-bold text-indigo-400 flex items-center gap-2">
                    <i class="fas fa-arrow-trend-up"></i> Program Progress Tertinggi
                </h2>
                <a href="{{ route('work-programs.index') }}" class="text-xs text-indigo-400 hover:underline">
                    Lihat Semua →
                </a>
            </div>
            @if($topPrograms->count())
            <div class="space-y-3">
                @foreach($topPrograms as $prog)
                @php
                    $pc2 = $prog->progress >= 100 ? 'emerald' : ($prog->progress >= 60 ? 'blue' : ($prog->progress >= 30 ? 'amber' : 'red'));
                    $barMap = ['emerald' => 'bg-emerald-500', 'blue' => 'bg-blue-500', 'amber' => 'bg-amber-500', 'red' => 'bg-red-500'];
                    $bar2 = $barMap[$pc2] ?? 'bg-indigo-500';
                @endphp
                <a href="{{ route('work-programs.show', $prog->id) }}"
                   class="flex items-center gap-4 p-3 rounded-xl hover:bg-indigo-500/5 transition-all group">
                    <div class="w-10 h-10 rounded-xl {{ $bar2 }}/15 flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-black text-{{ $pc2 }}-400">{{ $prog->progress }}%</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-theme-main truncate group-hover:text-indigo-400 transition-colors">{{ $prog->name }}</p>
                        <p class="text-xs text-theme-muted">{{ $prog->pj }} · {{ $prog->unit }}</p>
                        <div class="mt-1.5 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full {{ $bar2 }}" style="width: {{ $prog->progress }}%"></div>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-xs text-theme-muted group-hover:text-indigo-400 transition-colors"></i>
                </a>
                @endforeach
            </div>
            @else
            <p class="text-center text-theme-muted italic text-sm py-8">Belum ada data program kerja.</p>
            @endif

            {{-- At-Risk Programs --}}
            @if($atRiskPrograms->count())
            <div class="mt-5 pt-5 border-t border-gray-200 dark:border-gray-700/50">
                <h3 class="text-sm font-bold text-red-400 flex items-center gap-2 mb-3">
                    <i class="fas fa-triangle-exclamation"></i> Perlu Perhatian (Progress &lt; 30%)
                </h3>
                <div class="space-y-2">
                    @foreach($atRiskPrograms as $prog)
                    <a href="{{ route('work-programs.show', $prog->id) }}"
                       class="flex items-center justify-between p-2.5 rounded-lg bg-red-500/5 border border-red-500/10 hover:bg-red-500/10 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse flex-shrink-0"></div>
                            <span class="text-xs font-semibold text-theme-main truncate max-w-[200px]">{{ $prog->name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-black text-red-400">{{ $prog->progress }}%</span>
                            <i class="fas fa-arrow-right text-[10px] text-red-400/60 group-hover:text-red-400 transition-colors"></i>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Recent Activity --}}
        <div class="glass rounded-2xl border border-white/10 p-6">
            <h2 class="text-base font-bold text-indigo-400 flex items-center gap-2 mb-5">
                <i class="fas fa-clock-rotate-left"></i> Aktivitas Terbaru
            </h2>
            @if($recentUpdates->count())
            <div class="space-y-0">
                @foreach($recentUpdates as $update)
                <div class="relative pl-5 {{ !$loop->last ? 'pb-4' : '' }}">
                    {{-- Timeline line --}}
                    @if(!$loop->last)
                    <div class="absolute left-[5px] top-3 bottom-0 w-0.5 bg-gray-200 dark:bg-gray-700/50"></div>
                    @endif
                    {{-- Dot --}}
                    <div class="absolute left-0 top-1.5 w-2.5 h-2.5 rounded-full bg-indigo-500 ring-2 ring-indigo-400/30"></div>

                    <div>
                        <p class="text-xs font-semibold text-theme-main leading-snug">
                            {{ \Illuminate\Support\Str::limit($update->workProgram->name ?? '—', 30) }}
                        </p>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="text-[10px] font-black text-indigo-400">{{ $update->progress_before }}%</span>
                            <i class="fas fa-arrow-right text-[8px] text-theme-muted"></i>
                            <span class="text-[10px] font-black text-emerald-400">{{ $update->progress_after }}%</span>
                        </div>
                        @if($update->note)
                        <p class="text-[10px] text-theme-muted mt-0.5 leading-snug line-clamp-2 italic">{{ $update->note }}</p>
                        @endif
                        <p class="text-[10px] text-theme-muted mt-0.5">{{ $update->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-10">
                <i class="fas fa-history text-3xl text-theme-muted opacity-20 mb-2 block"></i>
                <p class="text-sm text-theme-muted italic">Belum ada aktivitas.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- ROW 4: Visi Misi + Sasaran Mutu                            --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Visi & Misi --}}
        <div class="glass rounded-2xl border border-white/10 p-6">
            <h2 class="text-base font-bold text-indigo-400 flex items-center gap-2 mb-5">
                <i class="fas fa-bullseye"></i> Visi & Misi
            </h2>
            @if($visi)
            <div class="mb-4">
                <p class="text-[11px] text-theme-muted font-bold uppercase tracking-wider mb-1.5">Visi</p>
                <p class="text-sm text-theme-main font-semibold leading-relaxed border-l-2 border-indigo-500 pl-3">
                    {{ $visi->content }}
                </p>
            </div>
            @endif
            @if($misi->count())
            <div>
                <p class="text-[11px] text-theme-muted font-bold uppercase tracking-wider mb-2">Misi</p>
                <ul class="space-y-1.5">
                    @foreach($misi as $m)
                    <li class="flex items-start gap-2 text-sm text-theme-main">
                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                        <span class="leading-snug">{{ $m->content }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if(!$visi && $misi->isEmpty())
            <p class="text-sm text-theme-muted italic text-center py-4">Belum ada data visi misi.</p>
            @endif
        </div>

        {{-- Sasaran Mutu --}}
        <div class="glass rounded-2xl border border-white/10 p-6">
            <h2 class="text-base font-bold text-indigo-400 flex items-center gap-2 mb-5">
                <i class="fas fa-award"></i> Sasaran Mutu
            </h2>
            @if($targets->count())
            <div class="space-y-3 max-h-64 overflow-y-auto custom-scrollbar pr-1">
                @foreach($targets as $t)
                <div class="bg-white/30 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl p-3">
                    <p class="text-xs font-bold text-theme-main leading-snug">{{ $t->sasaran }}</p>
                    <div class="flex flex-wrap gap-x-3 gap-y-1 mt-1.5">
                        @if($t->target)
                        <span class="text-[10px] text-emerald-400 font-semibold">
                            <i class="fas fa-flag mr-0.5"></i>{{ $t->target }}
                        </span>
                        @endif
                        @if($t->periode)
                        <span class="text-[10px] text-blue-400 font-semibold">
                            <i class="fas fa-calendar mr-0.5"></i>{{ $t->periode }}
                        </span>
                        @endif
                        @if($t->metode)
                        <span class="text-[10px] text-purple-400 font-semibold">
                            <i class="fas fa-cog mr-0.5"></i>{{ $t->metode }}
                        </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-theme-muted italic text-center py-4">Belum ada data sasaran mutu.</p>
            @endif
        </div>
    </div>

</div>
@endsection

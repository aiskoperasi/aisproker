@extends('layouts.app')

@section('title', 'Sasaran Mutu')
@section('page-title', 'Analisis Sasaran Mutu')

@section('content')
<div class="space-y-6" x-data="qualityTargetManager()">
    {{-- Header Content --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-theme-main tracking-tight flex items-center gap-2">
                <i class="fas fa-chart-pie text-indigo-500"></i>
                Sasaran Mutu Operasional
            </h2>
            <p class="text-sm text-theme-muted">Pantau pencapaian KPI dan standar mutu lembaga secara real-time.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('export.quality') }}" target="_blank" class="bg-emerald-500/10 hover:bg-emerald-500 text-emerald-500 hover:text-white px-5 py-2.5 rounded-xl text-sm font-bold border border-emerald-500/20 transition-all flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <button @click="openAddModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-indigo-500/20 transition-all flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Sasaran
            </button>
        </div>
    </div>

    {{-- Stats Grid --}}
    {{-- ... (same as before) ... --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass p-6 rounded-2xl border border-white/10 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all"></div>
            <p class="text-xs font-black text-theme-muted uppercase tracking-widest mb-1">Total Sasaran</p>
            <p class="text-3xl font-black text-theme-main">{{ $targets->count() }} <span class="text-sm font-medium text-theme-muted ml-1 italic">Item</span></p>
        </div>
        <div class="glass p-6 rounded-2xl border border-white/10 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all"></div>
            <p class="text-xs font-black text-theme-muted uppercase tracking-widest mb-1">Rata-rata Pencapaian</p>
            @php
                $avgAchievement = $targets->count() > 0 ? $targets->avg('achievement') : 0;
                $avgTarget = $targets->count() > 0 ? $targets->avg('target') : 0;
                $overallProgress = $avgTarget > 0 ? ($avgAchievement / $avgTarget) * 100 : 0;
            @endphp
            <p class="text-3xl font-black text-emerald-500">{{ number_format($avgAchievement, 1) }}%</p>
        </div>
        <div class="glass p-6 rounded-2xl border border-white/10 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl group-hover:bg-amber-500/20 transition-all"></div>
            <p class="text-xs font-black text-theme-muted uppercase tracking-widest mb-1">Efektivitas Mutu</p>
            <p class="text-3xl font-black text-amber-500">{{ number_format($overallProgress, 1) }}%</p>
        </div>
    </div>

    {{-- Chart Section --}}
    <div class="glass p-8 rounded-3xl border border-white/10 shadow-2xl overflow-hidden relative">
        <div class="flex items-center justify-between mb-8">
            <h3 class="font-bold text-theme-main flex items-center gap-2">
                <i class="fas fa-chart-column text-indigo-500"></i>
                Perbandingan Target & Realisasi
            </h3>
            <div class="flex items-center gap-4 text-[10px] font-bold uppercase tracking-widest leading-none">
                <div class="flex items-center gap-1.5 text-indigo-400">
                    <span class="w-3 h-3 rounded bg-indigo-500/40 border border-indigo-500"></span> Target
                </div>
                <div class="flex items-center gap-1.5 text-emerald-400">
                    <span class="w-3 h-3 rounded bg-emerald-500/40 border border-emerald-500"></span> Realisasi
                </div>
            </div>
        </div>
        <div class="h-80 w-full">
            <canvas id="qualityChart"></canvas>
        </div>
    </div>

    {{-- Detailed List --}}
    <div class="grid grid-cols-1 gap-4">
        @foreach($targets as $target)
        @php
            $progress = $target->target > 0 ? ($target->achievement / $target->target) * 100 : 0;
            $color = 'indigo';
            if ($progress >= 100) $color = 'emerald';
            elseif ($progress >= 75) $color = 'blue';
            elseif ($progress >= 50) $color = 'amber';
            else $color = 'rose';

            $colorClasses = [
                'indigo'  => ['bar' => 'bg-indigo-500',  'text' => 'text-indigo-500',  'bg' => 'bg-indigo-500/10'],
                'emerald' => ['bar' => 'bg-emerald-500', 'text' => 'text-emerald-500', 'bg' => 'bg-emerald-500/10'],
                'blue'    => ['bar' => 'bg-blue-500',    'text' => 'text-blue-500',    'bg' => 'bg-blue-500/10'],
                'amber'   => ['bar' => 'bg-amber-500',   'text' => 'text-amber-500',   'bg' => 'bg-amber-500/10'],
                'rose'    => ['bar' => 'bg-rose-500',    'text' => 'text-rose-500',    'bg' => 'bg-rose-500/10'],
            ];
            $cls = $colorClasses[$color];
        @endphp
        <div class="glass p-5 rounded-2xl border border-white/10 hover:border-indigo-500/30 transition-all group overflow-hidden relative">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex-1 space-y-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-black uppercase tracking-tighter px-2 py-0.5 rounded {{ $cls['bg'] }} {{ $cls['text'] }}">
                            {{ $target->periode }}
                        </span>
                        <span class="text-[9px] font-bold text-indigo-400 bg-indigo-500/5 px-2 py-0.5 rounded border border-indigo-500/10 flex items-center gap-1" title="Data disinkronkan otomatis dari Program Kerja">
                            <i class="fas fa-sync-alt animate-spin-slow"></i> Auto Sync
                        </span>
                    </div>
                    <h4 class="font-bold text-theme-main text-sm leading-snug group-hover:text-indigo-400 transition-colors">
                        {{ $target->sasaran }}
                    </h4>
                    <p class="text-xs text-theme-muted italic line-clamp-1 group-hover:line-clamp-none transition-all">
                        <i class="fas fa-microscope mr-1 opacity-50"></i> {{ $target->metode }}
                    </p>
                </div>

                <div class="w-full md:w-64 space-y-2">
                    <div class="flex justify-between items-end mb-1">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold text-theme-muted uppercase tracking-widest">Pencapaian</span>
                            <span class="text-lg font-black {{ $cls['text'] }}">{{ number_format($target->achievement, 1) }}%</span>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-[9px] font-bold text-theme-muted uppercase tracking-widest">Target</span>
                            <span class="text-sm font-bold text-theme-main opacity-60">{{ number_format($target->target, 0) }}%</span>
                        </div>
                    </div>
                    <div class="w-full h-1.5 bg-gray-200 dark:bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full {{ $cls['bar'] }} rounded-full transition-all duration-1000 group-hover:brightness-110"
                             style="width: {{ min($progress, 100) }}%"></div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-black text-theme-muted">{{ number_format($progress, 0) }}% dari target</span>
                        <div class="flex items-center gap-2">
                            <button @click="openEditModal({{ $target->id }})" class="text-indigo-500 hover:text-indigo-600 transition-colors">
                                <i class="fas fa-edit text-[10px]"></i> Edit
                            </button>
                            <form action="{{ route('quality-targets.destroy', $target->id) }}" method="POST" onsubmit="return confirm('Hapus sasaran mutu ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-600 transition-colors">
                                    <i class="fas fa-trash text-[10px]"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Modal Form (Add/Edit) --}}
    <div x-show="modalOpen" 
         class="fixed inset-0 z-[60] flex items-center justify-center p-4"
         x-cloak>
        <div x-show="modalOpen" x-transition.opacity class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="modalOpen = false"></div>
        
        <div x-show="modalOpen" x-transition.scale.origin.bottom class="glass w-full max-w-lg rounded-3xl border border-white/20 shadow-2xl relative overflow-hidden">
            <div class="p-6 border-b border-white/10 flex items-center justify-between">
                <h3 class="text-lg font-black text-theme-main" x-text="editMode ? 'Edit Sasaran Mutu' : 'Tambah Sasaran Mutu'"></h3>
                <button @click="modalOpen = false" class="w-8 h-8 rounded-xl bg-white/5 flex items-center justify-center text-theme-muted hover:text-rose-500 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form :action="formUrl" method="POST" class="p-6 space-y-4">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="space-y-1">
                    <label class="text-[10px] font-black text-theme-muted uppercase tracking-widest ml-1">Sasaran Mutu</label>
                    <textarea name="sasaran" x-model="formData.sasaran" required
                              class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                              rows="3" placeholder="Contoh: Terlaksananya 90% Pengadaan..."></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-theme-muted uppercase tracking-widest ml-1">Target (%)</label>
                        <input type="number" step="0.01" name="target" x-model="formData.target" required
                               class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                               placeholder="95.00">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-theme-muted uppercase tracking-widest ml-1">Realisasi (%)</label>
                        <input type="number" step="0.01" name="achievement" x-model="formData.achievement"
                               class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                               placeholder="0.00">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-black text-theme-muted uppercase tracking-widest ml-1">Periode</label>
                    <input type="text" name="periode" x-model="formData.periode" required
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                           placeholder="Contoh: Tahunan dan Bulanan">
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-black text-theme-muted uppercase tracking-widest ml-1">Metode Pengukuran</label>
                    <input type="text" name="metode" x-model="formData.metode" required
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                           placeholder="Contoh: Melaksanakan permintaan pengadaan...">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-indigo-500/20 transition-all flex items-center justify-center gap-2">
                        <i :class="editMode ? 'fas fa-save' : 'fas fa-plus'"></i>
                        <span x-text="editMode ? 'Simpan Perubahan' : 'Tambah Sasaran'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function qualityTargetManager() {
        return {
            modalOpen: false,
            editMode: false,
            formUrl: '{{ route("quality-targets.store") }}',
            formData: {
                sasaran: '',
                target: '',
                achievement: '',
                periode: '',
                metode: ''
            },
            openAddModal() {
                this.editMode = false;
                this.formUrl = '{{ route("quality-targets.store") }}';
                this.formData = { sasaran: '', target: '', achievement: '0', periode: '', metode: '' };
                this.modalOpen = true;
            },
            async openEditModal(id) {
                this.editMode = true;
                this.formUrl = `{{ url('quality-targets') }}/${id}`;
                
                try {
                    const response = await fetch(`{{ url('quality-targets') }}/${id}/edit`);
                    const data = await response.json();
                    this.formData = {
                        sasaran: data.sasaran,
                        target: data.target,
                        achievement: data.achievement,
                        periode: data.periode,
                        metode: data.metode
                    };
                    this.modalOpen = true;
                } catch (error) {
                    alert('Gagal mengambil data sasaran mutu.');
                }
            }
        };
    }

    document.addEventListener('DOMContentLoaded', function() {
        {{-- ... (same chart logic as before) ... --}}
        const ctx = document.getElementById('qualityChart').getContext('2d');
        const labels = @json($targets->pluck('sasaran')->map(fn($s) => strlen($s) > 25 ? substr($s, 0, 25) . '...' : $s));
        const targetData = @json($targets->pluck('target'));
        const achievementData = @json($targets->pluck('achievement'));
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#9CA3AF' : '#64748B';
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Target',
                        data: targetData,
                        backgroundColor: 'rgba(99, 102, 241, 0.4)',
                        borderColor: '#6366f1',
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.6,
                    },
                    {
                        label: 'Realisasi',
                        data: achievementData,
                        backgroundColor: 'rgba(16, 185, 129, 0.4)',
                        borderColor: '#10b981',
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? '#1F2937' : '#FFFFFF',
                        titleColor: isDark ? '#F3F4F6' : '#111827',
                        bodyColor: isDark ? '#9CA3AF' : '#4B5563',
                        borderColor: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 6,
                        usePointStyle: true,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: { color: gridColor, drawBorder: false },
                        ticks: { color: textColor, font: { size: 10, weight: '600' }, callback: value => value + '%' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor, font: { size: 9, weight: '600' }, autoSkip: false, maxRotation: 45, minRotation: 45 }
                    }
                }
            }
        });
    });
</script>
@endpush

@extends('layouts.app')

@section('title', $program->name . ' — Monitoring')
@section('page-title', 'Detail Program Kerja')

@section('content')
@php
    $colorMap = [
        'emerald' => ['bar' => 'from-emerald-400 to-emerald-600', 'text' => 'text-emerald-400', 'ring' => 'stroke-emerald-400'],
        'blue'    => ['bar' => 'from-blue-400 to-blue-600',       'text' => 'text-blue-400',    'ring' => 'stroke-blue-400'],
        'amber'   => ['bar' => 'from-amber-400 to-amber-500',     'text' => 'text-amber-400',   'ring' => 'stroke-amber-400'],
        'red'     => ['bar' => 'from-red-400 to-red-600',         'text' => 'text-red-400',     'ring' => 'stroke-red-400'],
    ];
    $c = $colorMap[$program->progress_color] ?? $colorMap['blue'];

    $statusLabel = [
        'planning'    => ['label' => 'Planning',    'bg' => 'bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400'],
        'on_progress' => ['label' => 'On Progress', 'bg' => 'bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400'],
        'done'        => ['label' => 'Selesai',     'bg' => 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400'],
        'cancelled'   => ['label' => 'Dibatalkan',  'bg' => 'bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400'],
    ];
    $st = $statusLabel[$program->status] ?? $statusLabel['planning'];

    // SVG circle progress
    $radius = 52;
    $circ = 2 * M_PI * $radius;
    $dash = $circ - ($program->progress / 100 * $circ);
@endphp

<div class="space-y-6">

    {{-- Back Button --}}
    <a href="{{ route('work-programs.index') }}"
       class="inline-flex items-center gap-2 text-sm text-theme-muted hover:text-indigo-400 transition-colors">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
    </a>



    {{-- Top Row: Info Card + Progress Ring --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Program Info Card --}}
        <div class="lg:col-span-2 glass p-6 rounded-2xl border border-white/10 space-y-4">
            <div>
                <h1 class="text-2xl font-black text-theme-main mb-1">{{ $program->name }}</h1>
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase {{ $st['bg'] }}">
                    {{ $st['label'] }}
                </span>
            </div>

            @if($program->description)
            <p class="text-theme-muted text-sm leading-relaxed">{{ $program->description }}</p>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 pt-2">
                <div>
                    <p class="text-xs text-theme-muted uppercase tracking-wider font-bold">Penanggung Jawab</p>
                    <p class="font-semibold text-theme-main mt-0.5">{{ $program->pj ?: '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-theme-muted uppercase tracking-wider font-bold">Unit</p>
                    <p class="font-semibold text-indigo-400 mt-0.5">{{ $program->unit ?: '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-theme-muted uppercase tracking-wider font-bold">Timeline</p>
                    <p class="font-semibold text-theme-main mt-0.5">{{ $program->timeline ?: '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-theme-muted uppercase tracking-wider font-bold">Anggaran</p>
                    <p class="font-semibold text-emerald-400 mt-0.5">Rp {{ number_format($program->budget, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-theme-muted uppercase tracking-wider font-bold">Realisasi</p>
                    <p class="font-semibold text-blue-400 mt-0.5">Rp {{ number_format($program->realization, 0, ',', '.') }}</p>
                </div>
                @if($program->indicators)
                <div class="col-span-full">
                    <p class="text-xs text-theme-muted uppercase tracking-wider font-bold">Indikator Kinerja</p>
                    <p class="text-sm text-theme-main mt-0.5">{{ $program->indicators }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Progress Ring --}}
        <div class="glass p-6 rounded-2xl border border-white/10 flex flex-col items-center justify-center text-center">
            <p class="text-xs text-theme-muted uppercase tracking-wider font-bold mb-4">Progress</p>
            <div class="relative w-36 h-36">
                <svg class="w-full h-full -rotate-90" viewBox="0 0 120 120">
                    <circle cx="60" cy="60" r="{{ $radius }}" fill="none"
                            stroke="currentColor" class="text-gray-200 dark:text-gray-700" stroke-width="10"/>
                    <circle cx="60" cy="60" r="{{ $radius }}" fill="none"
                            class="{{ $c['ring'] }}" stroke-width="10"
                            stroke-linecap="round"
                            stroke-dasharray="{{ number_format($circ, 2) }}"
                            stroke-dashoffset="{{ number_format($dash, 2) }}"
                            style="transition: stroke-dashoffset 1s ease;"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-3xl font-black {{ $c['text'] }}">{{ $program->progress }}%</span>
                </div>
            </div>
            <p class="mt-3 text-sm text-theme-muted">{{ $program->updates->count() }} pembaruan</p>
        </div>
    </div>

    {{-- Bottom Row: Update Form + SOP + History --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Indicators Section --}}
        <div class="lg:col-span-2 glass p-6 rounded-2xl border border-white/10 space-y-5">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-bold text-indigo-400 flex items-center gap-2">
                    <i class="fas fa-list-check"></i> Indikator Pencapaian
                </h2>
                <button type="button" onclick="openIndicatorModal()"
                        class="bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1">
                    <i class="fas fa-plus"></i> Tambah Indikator
                </button>
            </div>

            @if($program->weightedIndicators->count() > 0)
                <div class="space-y-4">
                    @foreach($program->weightedIndicators as $indicator)
                        <div class="p-4 rounded-xl bg-white/5 border border-white/10 space-y-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-theme-main text-sm">{{ $indicator->name }}</h3>
                                    <p class="text-[10px] text-theme-muted uppercase tracking-wider font-bold">Bobot: {{ $indicator->weight }}%</p>
                                </div>
                                <div class="flex gap-1">
                                    <button onclick="editIndicator({{ $indicator->id }}, '{{ addslashes($indicator->name) }}', {{ $indicator->weight }}, '{{ addslashes($indicator->target) }}')"
                                            class="p-1.5 text-blue-400 hover:bg-blue-400/10 rounded-lg transition-all">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <form action="{{ route('work-programs.indicators.destroy', [$program->id, $indicator->id]) }}" method="POST" onsubmit="return confirm('Hapus indikator ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-400 hover:bg-red-400/10 rounded-lg transition-all">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            @if($indicator->target)
                                <p class="text-xs text-theme-muted italic">"{{ $indicator->target }}"</p>
                            @endif

                            <div x-data="{ val: {{ $indicator->achievement }} }" class="space-y-2">
                                <form action="{{ route('work-programs.indicators.achievement', [$program->id, $indicator->id]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-[10px] font-bold text-theme-muted uppercase">Capaian</span>
                                        <span class="text-xs font-black text-indigo-400" x-text="val + '%'"></span>
                                    </div>
                                    <div class="flex gap-3 items-center">
                                        <input type="range" name="achievement" min="0" max="100" x-model="val"
                                               class="flex-1 h-1.5 rounded-full appearance-none bg-gray-200 dark:bg-gray-700 accent-indigo-500 cursor-pointer">
                                        <button type="submit" class="bg-indigo-500 text-white px-3 py-1 rounded-lg text-[10px] font-bold hover:bg-indigo-600 transition-all">
                                            Update
                                        </button>
                                    </div>
                                    <div class="mt-2 flex items-center justify-between">
                                        @if($indicator->evidence_file)
                                            <a href="{{ asset('storage/' . $indicator->evidence_file) }}" target="_blank"
                                               class="text-[10px] text-emerald-400 hover:underline flex items-center gap-1">
                                                <i class="fas fa-paperclip"></i> Lihat Bukti
                                            </a>
                                        @else
                                            <span class="text-[10px] text-theme-muted italic">Belum ada bukti</span>
                                        @endif
                                        <input type="file" name="evidence_file" class="hidden" id="evidence-{{ $indicator->id }}" onchange="this.form.submit()">
                                        <label for="evidence-{{ $indicator->id }}" class="text-[10px] text-indigo-400 hover:underline cursor-pointer flex items-center gap-1">
                                            <i class="fas fa-upload"></i> {{ $indicator->evidence_file ? 'Ganti Bukti' : 'Upload Bukti' }}
                                        </label>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 bg-white/5 rounded-2xl border border-dashed border-white/10">
                    <i class="fas fa-layer-group text-3xl text-theme-muted opacity-20 mb-3"></i>
                    <p class="text-sm text-theme-muted">Belum ada indikator. Silakan tambahkan untuk perhitungan progress otomatis.</p>
                </div>

                {{-- Original Slider if no indicators --}}
                <div class="pt-5 border-t border-white/5">
                    <h3 class="text-sm font-bold text-theme-muted mb-4 uppercase tracking-widest">Manual Progress (Tanpa Indikator)</h3>
                    <form action="{{ route('work-programs.update-progress', $program->id) }}" method="POST" class="space-y-5">
                        @csrf
                        <div x-data="{ val: {{ $program->getRawOriginal('progress') ?: 0 }} }">
                            <div class="flex justify-between mb-2">
                                <label class="text-sm font-semibold text-theme-main">Progress (%)</label>
                                <span class="text-sm font-black text-indigo-400" x-text="val + '%'"></span>
                            </div>
                            <input type="range" name="progress" min="0" max="100" x-model="val"
                                   class="w-full h-2 rounded-full appearance-none bg-gray-200 dark:bg-gray-700 accent-indigo-500 cursor-pointer">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-semibold text-theme-main block mb-2">Status</label>
                                <select name="status" class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 text-theme-main text-sm outline-none">
                                    <option value="planning" {{ $program->status === 'planning' ? 'selected' : '' }}>⏳ Planning</option>
                                    <option value="on_progress" {{ $program->status === 'on_progress' ? 'selected' : '' }}>🔵 On Progress</option>
                                    <option value="done" {{ $program->status === 'done' ? 'selected' : '' }}>✅ Selesai</option>
                                    <option value="cancelled" {{ $program->status === 'cancelled' ? 'selected' : '' }}>❌ Dibatalkan</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 rounded-xl transition-all shadow-lg shadow-indigo-500/25">
                                    <i class="fas fa-save mr-1"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        {{-- Side Column: Realization + SOP + History --}}
        <div class="space-y-6">
            {{-- Update Realization Card --}}
            <div class="glass p-6 rounded-2xl border border-white/10 space-y-5">
                <h2 class="text-lg font-bold text-emerald-400 flex items-center gap-2">
                    <i class="fas fa-money-bill-trend-up"></i> Realisasi Biaya
                </h2>

                <form action="{{ route('work-programs.update-realization', $program->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-theme-muted font-bold">Rp</span>
                            <input type="number" name="realization" value="{{ (int)$program->realization }}" min="0" step="any"
                                   class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 text-theme-main text-sm font-bold focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none">
                        </div>
                        <p class="mt-2 text-[10px] text-theme-muted italic">Anggaran: Rp {{ number_format($program->budget, 0, ',', '.') }}</p>
                    </div>

                    <button type="submit"
                            class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2.5 rounded-xl transition-all shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2">
                        <i class="fas fa-check-double"></i> Update
                    </button>
                </form>
            </div>

            {{-- SOP Card --}}
            <div class="glass p-5 rounded-2xl border border-white/10">
                <h3 class="text-base font-bold text-indigo-400 flex items-center gap-2 mb-4">
                    <i class="fas fa-file-pdf"></i> Dokumen SOP
                </h3>

                @if($program->sop_file)
                    <div class="flex flex-col gap-3 mb-4">
                        <div class="flex items-center gap-2 bg-emerald-50 dark:bg-emerald-900/20 px-3 py-2 rounded-lg">
                            <i class="fas fa-file-alt text-emerald-500"></i>
                            <span class="text-xs text-emerald-700 dark:text-emerald-400 truncate">{{ basename($program->sop_file) }}</span>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ asset('storage/' . $program->sop_file) }}" target="_blank"
                               class="flex-1 text-center bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-500 py-2 rounded-lg text-xs font-semibold transition-all">
                                <i class="fas fa-download mr-1"></i> Download
                            </a>
                            <form action="{{ route('work-programs.delete-sop', $program->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus file SOP?')"
                                        class="bg-red-500/10 hover:bg-red-500/20 text-red-500 p-2 rounded-lg text-xs transition-all">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <p class="text-xs text-theme-muted mb-3 italic">Belum ada dokumen SOP.</p>
                @endif

                <form action="{{ route('work-programs.upload-sop', $program->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="sop_file" id="sop_file" class="hidden" onchange="this.form.submit()">
                    <label for="sop_file" class="w-full text-center block bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 py-2 rounded-lg text-xs font-semibold transition-all cursor-pointer">
                        <i class="fas fa-upload mr-1"></i> {{ $program->sop_file ? 'Ganti SOP' : 'Upload SOP' }}
                    </label>
                </form>
            </div>

            {{-- Update History --}}
            <div class="glass p-5 rounded-2xl border border-white/10">
                <h3 class="text-base font-bold text-indigo-400 flex items-center gap-2 mb-4">
                    <i class="fas fa-history"></i> Riwayat
                </h3>

                @if($program->updates->count() > 0)
                    <div class="space-y-3 max-h-72 overflow-y-auto custom-scrollbar pr-1">
                        @foreach($program->updates as $update)
                            <div class="relative pl-5 before:content-[''] before:absolute before:left-0 before:top-2 before:w-2.5 before:h-2.5 before:rounded-full before:bg-indigo-400 before:ring-2 before:ring-indigo-400/30">
                                @if(!$loop->last)
                                    <div class="absolute left-[4px] top-4 w-0.5 h-full bg-gray-200 dark:bg-gray-700/50"></div>
                                @endif
                                <div class="pb-3 border-b border-white/5 last:border-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-black text-indigo-400">{{ $update->progress_before }}% → {{ $update->progress_after }}%</span>
                                        <span class="text-[9px] bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 px-1.5 py-0.5 rounded">
                                            {{ str_replace('_', ' ', ucfirst($update->status_after)) }}
                                        </span>
                                    </div>
                                    @if($update->note)
                                        <p class="text-[11px] text-theme-muted leading-tight">{{ $update->note }}</p>
                                    @endif
                                    <p class="text-[9px] text-gray-500 mt-1 uppercase font-bold">
                                        {{ $update->created_at->translatedFormat('d M Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-theme-muted italic text-center py-4">Belum ada riwayat.</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Indicator Modal --}}
<div id="indicatorModal" class="fixed inset-0 z-[60] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="glass w-full max-w-lg p-6 rounded-2xl border border-white/10 space-y-5 animate-in fade-in zoom-in duration-200">
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-black text-theme-main" id="indicatorModalTitle">Tambah Indikator</h3>
            <button onclick="closeIndicatorModal()" class="text-theme-muted hover:text-red-400 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="indicatorForm" action="{{ route('work-programs.indicators.store', $program->id) }}" method="POST" class="space-y-4">
            @csrf
            <div id="methodField"></div>
            <div>
                <label class="text-xs font-bold text-theme-muted uppercase mb-1.5 block">Nama Indikator</label>
                <input type="text" name="name" id="indicator_name" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 text-theme-main text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-theme-muted uppercase mb-1.5 block">Bobot (%)</label>
                    <input type="number" name="weight" id="indicator_weight" min="0" max="100" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 text-theme-main text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div class="flex items-end">
                    <p class="text-[10px] text-theme-muted italic">Total bobot harus 100% untuk akurasi progress.</p>
                </div>
            </div>
            <div>
                <label class="text-xs font-bold text-theme-muted uppercase mb-1.5 block">Target / Metode Verifikasi</label>
                <textarea name="target" id="indicator_target" rows="3"
                          class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 text-theme-main text-sm resize-none focus:ring-2 focus:ring-indigo-500 outline-none"
                          placeholder="Misal: Terselesaikannya laporan akhir semester..."></textarea>
            </div>

            <button type="submit" class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-indigo-500/25">
                Simpan Indikator
            </button>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('indicatorModal');
    const form = document.getElementById('indicatorForm');
    const title = document.getElementById('indicatorModalTitle');
    const methodField = document.getElementById('methodField');

    function openIndicatorModal() {
        title.innerText = 'Tambah Indikator';
        form.action = "{{ route('work-programs.indicators.store', $program->id) }}";
        methodField.innerHTML = '';
        form.reset();
        modal.classList.remove('hidden');
    }

    function editIndicator(id, name, weight, target) {
        title.innerText = 'Edit Indikator';
        let action = "{{ route('work-programs.indicators.update', [$program->id, ':id']) }}";
        form.action = action.replace(':id', id);
        methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('indicator_name').value = name;
        document.getElementById('indicator_weight').value = weight;
        document.getElementById('indicator_target').value = target;
        modal.classList.remove('hidden');
    }

    function closeIndicatorModal() {
        modal.classList.add('hidden');
    }

    // Close on outside click
    window.onclick = function(event) {
        if (event.target == modal) closeIndicatorModal();
    }
</script>
        </div>
    </div>
</div>
@endsection

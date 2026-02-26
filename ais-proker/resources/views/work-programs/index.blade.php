@extends('layouts.app')

@section('title', 'Program Kerja')
@section('page-title', 'Program Kerja')

@section('content')
<div class="space-y-6">

    {{-- Header Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="glass p-5 rounded-2xl border border-white/10 text-center">
            <p class="text-xs text-theme-muted uppercase tracking-wider font-bold mb-1">Total Program</p>
            <p class="text-3xl font-black text-indigo-500">{{ $stats['total'] }}</p>
        </div>
        <div class="glass p-5 rounded-2xl border border-white/10 text-center">
            <p class="text-xs text-theme-muted uppercase tracking-wider font-bold mb-1">Rata-rata Progress</p>
            <p class="text-3xl font-black text-blue-500">{{ $stats['avg_progress'] }}%</p>
        </div>
        <div class="glass p-5 rounded-2xl border border-white/10 text-center">
            <p class="text-xs text-theme-muted uppercase tracking-wider font-bold mb-1">Selesai</p>
            <p class="text-3xl font-black text-emerald-500">{{ $stats['done'] }}</p>
        </div>
        <div class="glass p-5 rounded-2xl border border-white/10 text-center">
            <p class="text-xs text-theme-muted uppercase tracking-wider font-bold mb-1">On Progress</p>
            <p class="text-3xl font-black text-amber-500">{{ $stats['on_progress'] }}</p>
        </div>
    </div>

    {{-- Multi Filter Dropdowns --}}
    <div class="glass p-5 rounded-2xl border border-white/10 shadow-lg">
        <form action="{{ route('work-programs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label for="parent_program_id" class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Pilih Program</label>
                <select name="parent_program_id" id="parent_program_id" onchange="this.form.submit()"
                        style="color-scheme: dark;"
                        class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-300 dark:border-white/10 rounded-xl px-4 py-2.5 text-xs text-theme-main focus:ring-2 focus:ring-indigo-500 transition-all outline-none">
                    <option value="" class="dark:bg-slate-900">Semua Program</option>
                    @foreach($allParentPrograms as $p)
                        <option value="{{ $p->id }}" class="dark:bg-slate-900" {{ request('parent_program_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="unit_id" class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Pilih Unit</label>
                <select name="unit_id" id="unit_id" onchange="this.form.submit()"
                        style="color-scheme: dark;"
                        class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-300 dark:border-white/10 rounded-xl px-4 py-2.5 text-xs text-theme-main focus:ring-2 focus:ring-indigo-500 transition-all outline-none">
                    <option value="" class="dark:bg-slate-900">Semua Unit</option>
                    @foreach($allUnits as $unit)
                        <option value="{{ $unit->id }}" class="dark:bg-slate-900" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Pilih Status</label>
                <select name="status" id="status" onchange="this.form.submit()"
                        style="color-scheme: dark;"
                        class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-300 dark:border-white/10 rounded-xl px-4 py-2.5 text-xs text-theme-main focus:ring-2 focus:ring-indigo-500 transition-all outline-none">
                    <option value="" class="dark:bg-slate-900">Semua Status</option>
                    <option value="planning" class="dark:bg-slate-900" {{ request('status') === 'planning' ? 'selected' : '' }}>Rencana (Planning)</option>
                    <option value="on_progress" class="dark:bg-slate-900" {{ request('status') === 'on_progress' ? 'selected' : '' }}>Berjalan (On Progress)</option>
                    <option value="done" class="dark:bg-slate-900" {{ request('status') === 'done' ? 'selected' : '' }}>Selesai (Done)</option>
                    <option value="cancelled" class="dark:bg-slate-900" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Batal (Cancelled)</option>
                </select>
            </div>

            <div>
                <a href="{{ route('work-programs.index') }}" 
                   class="flex items-center justify-center gap-2 w-full bg-indigo-500/10 hover:bg-indigo-500 text-indigo-500 hover:text-white py-2.5 rounded-xl text-xs font-bold transition-all border border-indigo-500/20">
                    <i class="fas fa-rotate-left"></i> Reset Filter
                </a>
            </div>
        </form>
    </div>

    {{-- Overall Progress Bar --}}
    <div class="glass p-5 rounded-2xl border border-white/10">
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-semibold text-theme-muted">Progress Keseluruhan</span>
            <span class="text-sm font-bold text-indigo-400">{{ $stats['avg_progress'] }}%</span>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
            <div class="h-3 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 transition-all duration-1000"
                 style="width: {{ $stats['avg_progress'] }}%"></div>
        </div>
        <div class="flex justify-between text-xs text-theme-muted mt-2">
            <span>Total Anggaran: <strong class="text-emerald-400">Rp {{ number_format($stats['total_budget'], 0, ',', '.') }}</strong></span>
            <span>{{ $stats['done'] }} dari {{ $stats['total'] }} program selesai</span>
        </div>
    </div>

    {{-- Program Table --}}
    <div class="glass p-6 rounded-2xl border border-white/10 shadow-xl overflow-hidden">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-indigo-400 flex items-center gap-2">
                <i class="fas fa-list-check"></i> 
                @if(request('parent_program_id'))
                    @php $p = $allParentPrograms->where('id', request('parent_program_id'))->first(); @endphp
                    Daftar Kegiatan: {{ $p->name ?? 'Program' }}
                @else
                    Daftar Semua Program Kerja
                @endif
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('export.report', array_merge(request()->all(), ['type' => 'detailed'])) }}" target="_blank" class="bg-emerald-500/10 hover:bg-emerald-500 text-emerald-500 hover:text-white text-sm font-bold px-4 py-2.5 rounded-xl transition-all border border-emerald-500/20 flex items-center gap-2">
                    <i class="fas fa-file-pdf"></i> <span class="hidden sm:inline">Export PDF</span>
                </a>
                <button onclick="openCreateModal()" class="bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-bold px-4 py-2.5 rounded-xl transition-all shadow-lg shadow-indigo-500/25 flex items-center gap-2">
                    <i class="fas fa-plus"></i> <span class="hidden sm:inline">Tambah Program</span>
                </button>
            </div>
        </div>

        {{-- ===== MOBILE CARD VIEW (hidden on md+) ===== --}}
        <div class="md:hidden space-y-6">
            @if($programs->count() > 0)
                @foreach($programs->groupBy('parent_program_id') as $pId => $group)
                @php $pModel = $allParentPrograms->where('id', $pId)->first(); @endphp
                <div class="space-y-3">
                    <div class="px-2 py-1 bg-indigo-500/10 rounded-lg inline-block">
                        <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest leading-none">
                            <i class="fas fa-folder mr-1"></i> {{ $pModel->name ?? 'Tanpa Kategori' }}
                        </span>
                    </div>

                    @foreach($group as $program)
                    @php
                        $color = $program->progress_color;
                        $colorMap = [
                            'emerald' => 'bg-emerald-500',
                            'blue'    => 'bg-blue-500',
                            'amber'   => 'bg-amber-500',
                            'red'     => 'bg-red-500',
                        ];
                        $barColor = $colorMap[$color] ?? 'bg-indigo-500';
                        $statusMap = [
                            'planning'    => ['label' => 'Planning',    'bg' => 'bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400'],
                            'on_progress' => ['label' => 'On Progress', 'bg' => 'bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400'],
                            'done'        => ['label' => 'Selesai',     'bg' => 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400'],
                            'cancelled'   => ['label' => 'Batal',       'bg' => 'bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400'],
                        ];
                        $st = $statusMap[$program->status] ?? $statusMap['planning'];
                    @endphp
                    <div class="bg-white/50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl p-4 space-y-3 shadow-sm">
                        {{-- Title + Status --}}
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-theme-main text-sm leading-tight">{{ $program->name }}</p>
                                @if($program->description && $program->description !== $program->name)
                                    <p class="text-[10px] text-theme-muted italic mt-0.5 leading-tight">{{ $program->description }}</p>
                                @endif
                            </div>
                            <span class="flex-shrink-0 px-2.5 py-1 rounded-full text-[10px] font-black uppercase {{ $st['bg'] }}">
                                {{ $st['label'] }}
                            </span>
                        </div>

                        {{-- Progress Bar --}}
                        <div>
                            <div class="flex justify-between text-xs text-theme-muted mb-1">
                                <span>Progress</span>
                                <span class="font-bold">{{ $program->progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                                <div class="h-2 rounded-full {{ $barColor }} transition-all duration-700"
                                     style="width: {{ $program->progress }}%"></div>
                            </div>
                        </div>

                        {{-- Meta Info --}}
                        <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-xs text-theme-muted">
                            <div>
                                <span>PJ: </span>
                                <span class="font-semibold text-theme-main">{{ $program->pj ?: '—' }}</span>
                            </div>
                            <div>
                                <span>Unit: </span>
                                <span class="font-bold text-indigo-500 uppercase">{{ $program->orgUnit->name ?? $program->unit ?: '—' }}</span>
                            </div>
                            <div>
                                <span>Timeline: </span>
                                <span class="text-theme-main">{{ $program->timeline ?: '—' }}</span>
                            </div>
                            <div>
                                <span>Anggaran: </span>
                                <span class="font-bold text-emerald-500 font-mono text-[11px]">Rp {{ number_format($program->budget, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Action --}}
                        <div class="flex gap-2">
                            <a href="{{ route('work-programs.show', $program->id) }}"
                               class="flex-1 flex items-center justify-center gap-2 bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-500 py-2 rounded-xl text-xs font-semibold transition-all">
                                <i class="fas fa-magnifying-glass"></i> Detail
                            </a>
                            <button type="button" onclick="openEditModal({{ $program->id }})"
                                    class="flex-1 flex items-center justify-center gap-2 bg-blue-500/10 hover:bg-blue-500/20 text-blue-500 py-2 rounded-xl text-xs font-semibold transition-all">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            @else
                <div class="py-12 text-center text-theme-muted italic">
                    <i class="fas fa-inbox text-4xl mb-3 block opacity-30"></i>
                    Belum ada program kerja.
                </div>
            @endif
        </div>

        {{-- ===== DESKTOP TABLE VIEW (hidden on mobile) ===== --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-gray-300 dark:border-gray-700 text-xs uppercase tracking-widest text-theme-muted bg-gray-50/50 dark:bg-transparent">
                        <th class="px-4 py-4 font-black">Program / Kegiatan</th>
                        <th class="px-4 py-4 font-black">PJ / Unit</th>
                        <th class="px-4 py-4 font-black">Timeline</th>
                        <th class="px-4 py-4 font-black text-right">Anggaran</th>
                        <th class="px-4 py-4 font-black text-center w-36">Progress</th>
                        <th class="px-4 py-4 font-black text-center">Status</th>
                        <th class="px-4 py-4 font-black text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
                    @if($programs->count() > 0)
                        @foreach($programs->groupBy('parent_program_id') as $pId => $group)
                        @php $pModel = $allParentPrograms->where('id', $pId)->first(); @endphp
                        {{-- Parent Group Header --}}
                        <tr class="bg-indigo-500/10 dark:bg-indigo-500/10 border-l-4 border-indigo-500 shadow-sm">
                            <td colspan="7" class="px-4 py-3 font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest text-[10px]">
                                <i class="fas fa-folder-open mr-2"></i> {{ $pModel->name ?? 'Program Tanpa Kategori' }}
                            </td>
                        </tr>
                        
                        @foreach($group as $program)
                        @php
                            $color = $program->progress_color;
                            $colorMap = [
                                'emerald' => 'bg-emerald-500',
                                'blue'    => 'bg-blue-500',
                                'amber'   => 'bg-amber-500',
                                'red'     => 'bg-red-500',
                            ];
                            $barColor = $colorMap[$color] ?? 'bg-indigo-500';
                            $statusMap = [
                                'planning'    => ['label' => 'Planning',    'bg' => 'bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400'],
                                'on_progress' => ['label' => 'On Progress', 'bg' => 'bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400'],
                                'done'        => ['label' => 'Selesai',     'bg' => 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400'],
                                'cancelled'   => ['label' => 'Batal',       'bg' => 'bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400'],
                            ];
                            $st = $statusMap[$program->status] ?? $statusMap['planning'];
                        @endphp
                        <tr class="hover:bg-white/40 dark:hover:bg-white/5 transition-colors">
                            <td class="px-4 py-4 pl-8">
                                <div class="font-semibold text-theme-main leading-tight">{{ $program->name }}</div>
                                @if($program->description && $program->description !== $program->name)
                                    <div class="text-[10px] text-theme-muted italic mt-0.5 leading-tight">{{ $program->description }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-theme-main">{{ $program->pj }}</div>
                                <div class="text-xs font-bold text-indigo-500 uppercase">{{ $program->orgUnit->name ?? $program->unit }}</div>
                            </td>
                            <td class="px-4 py-4 text-sm text-theme-muted">{{ $program->timeline }}</td>
                            <td class="px-4 py-4 text-right font-mono font-bold text-theme-main text-sm">
                                Rp {{ number_format($program->budget, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                                        <div class="h-2 rounded-full {{ $barColor }} transition-all duration-700"
                                             style="width: {{ $program->progress }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold w-8 text-right text-theme-muted">{{ $program->progress }}%</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase {{ $st['bg'] }}">
                                    {{ $st['label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('work-programs.show', $program->id) }}"
                                       class="inline-flex items-center gap-1 bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-500 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all"
                                       title="Detail">
                                        <i class="fas fa-magnifying-glass"></i>
                                    </a>
                                    <button type="button" 
                                            onclick="openEditModal({{ $program->id }})"
                                            class="inline-flex items-center gap-1 bg-blue-500/10 hover:bg-blue-500/20 text-blue-500 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all"
                                            title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('work-programs.destroy', $program->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus program ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center gap-1 bg-red-500/10 hover:bg-red-500/20 text-red-500 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="py-16 text-center text-theme-muted italic">
                                <i class="fas fa-inbox text-4xl mb-3 block opacity-30"></i>
                                Belum ada program kerja.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@push('scripts')
<script>
    function openEditModal(id) {
        const url = `{{ url('work-programs') }}/${id}/edit`;
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            document.getElementById('edit_form').action = `{{ url('work-programs') }}/${id}`;
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_parent_program_id').value = data.parent_program_id;
            document.getElementById('edit_description').value = data.description;
            document.getElementById('edit_indicators').value = data.indicators || '';
            document.getElementById('edit_pj').value = data.pj || '';
            document.getElementById('edit_timeline').value = data.timeline || '';
            document.getElementById('edit_budget').value = data.budget || 0;
            document.getElementById('edit_progress').value = data.progress;
            document.getElementById('edit_status').value = data.status;
            
            document.getElementById('edit_modal').classList.remove('hidden');
            document.getElementById('edit_modal').classList.add('flex');
        })
        .catch(error => {
            console.error('Error fetching program data:', error);
            alert('Gagal mengambil data program. Silakan coba lagi.');
        });
    }

    function closeEditModal() {
        document.getElementById('edit_modal').classList.add('hidden');
        document.getElementById('edit_modal').classList.remove('flex');
    }

    function openCreateModal() {
        document.getElementById('create_modal').classList.remove('hidden');
        document.getElementById('create_modal').classList.add('flex');
    }

    function closeCreateModal() {
        document.getElementById('create_modal').classList.add('hidden');
        document.getElementById('create_modal').classList.remove('flex');
    }
</script>
@endpush

{{-- Create Modal --}}
<div id="create_modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="glass w-full max-w-2xl rounded-3xl border border-white/20 shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center bg-indigo-500/10">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fas fa-plus text-indigo-400"></i> Tambah Program Kerja
            </h3>
            <button onclick="closeCreateModal()" class="text-white/50 hover:text-white transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('work-programs.store') }}" method="POST" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Judul Kegiatan / Program Kerja</label>
                    <input type="text" name="name" required placeholder="Contoh: Digitalisasi Arsip Unit IT"
                           class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Program Induk</label>
                    <select name="parent_program_id" required class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        <option value="">-- Pilih Program Induk --</option>
                        @foreach($allParentPrograms as $p)
                            <option value="{{ $p->id }}" {{ request('parent_program_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Deskripsi / Sasaran Kegiatan</label>
                    <textarea name="description" rows="2" required placeholder="Apa yang akan dilakukan?"
                              class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all"></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Indikator Keberhasilan</label>
                    <textarea name="indicators" rows="2" placeholder="Apa tanda kegiatan ini berhasil?"
                              class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all"></textarea>
                </div>
                
                <div>
                    <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Penanggung Jawab (PJ)</label>
                    <input type="text" name="pj" value="{{ Auth::user()->name }}"
                           class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                </div>
                
                @if(Auth::user()->unit_id === null)
                <div>
                    <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Unit Pelaksana</label>
                    <select name="unit_id" required class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        <option value="">-- Pilih Unit --</option>
                        @foreach($allUnits as $unit)
                            <option value="{{ $unit->id }}" {{ session('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                
                <div>
                    <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Timeline</label>
                    <input type="text" name="timeline" placeholder="Contoh: Juli - Agustus"
                           class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Anggaran (Rp)</label>
                    <input type="number" name="budget" value="0" min="0" step="any"
                           class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                </div>
                
                <input type="hidden" name="progress" value="0">
                <input type="hidden" name="status" value="planning">

            </div>
            
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeCreateModal()"
                        class="px-6 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-white text-sm font-bold transition-all border border-white/10">
                    Batal
                </button>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white text-sm font-bold shadow-lg shadow-emerald-500/20 transition-all">
                    Tambah Program
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div id="edit_modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="glass w-full max-w-2xl rounded-3xl border border-white/20 shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center bg-indigo-500/10">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fas fa-edit text-indigo-400"></i> Edit Program Kerja
            </h3>
            <button onclick="closeEditModal()" class="text-white/50 hover:text-white transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="edit_form" method="POST" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Judul Kegiatan / Program Kerja</label>
                    <input type="text" name="name" id="edit_name" required
                           class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Program Induk</label>
                    <select name="parent_program_id" id="edit_parent_program_id" required
                            class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        @foreach($allParentPrograms as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Deskripsi / Sasaran Kegiatan</label>
                    <textarea name="description" id="edit_description" rows="2" required
                              class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all"></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Indikator Keberhasilan</label>
                    <textarea name="indicators" id="edit_indicators" rows="2"
                              class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all"></textarea>
                </div>
                
                <div>
                    <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Penanggung Jawab (PJ)</label>
                    <input type="text" name="pj" id="edit_pj"
                           class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                </div>
                
                
                <div>
                    <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Timeline</label>
                    <input type="text" name="timeline" id="edit_timeline"
                           class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Anggaran (Rp)</label>
                    <input type="number" name="budget" id="edit_budget" min="0" step="any"
                           class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Progress (%)</label>
                    <input type="number" name="progress" id="edit_progress" min="0" max="100" required
                           class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Status</label>
                    <select name="status" id="edit_status" required
                            class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        <option value="planning">Rencana (Planning)</option>
                        <option value="on_progress">Berjalan (On Progress)</option>
                        <option value="done">Selesai (Done)</option>
                        <option value="cancelled">Batal (Cancelled)</option>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeEditModal()"
                        class="px-6 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-white text-sm font-bold transition-all border border-white/10">
                    Batal
                </button>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white text-sm font-bold shadow-lg shadow-indigo-500/20 transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Visi, Misi & Sasaran Mutu')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4 flex justify-between items-end">
        <div>
            <h1 class="glow-text text-indigo-600 dark:text-indigo-400 mb-2">Visi & Misi</h1>
            <p class="text-gray-600 dark:text-gray-400 font-bold uppercase tracking-widest text-[10px]">
                Unit: <span class="text-indigo-500">{{ session('unit_name') ?? 'Global' }}</span> | 
                Tahun Akademik: <span class="text-indigo-500">{{ session('school_year_name') ?? 'Aktif' }}</span>
            </p>
        </div>
        <div class="flex gap-2">
            @if(!$visi)
                <button onclick="openVisiModal()" class="bg-indigo-500 hover:bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl transition-all shadow-lg">
                    <i class="fas fa-plus mr-1"></i> Tambah Visi
                </button>
            @endif
            <button onclick="openMisiModal()" class="bg-emerald-500 hover:bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl transition-all shadow-lg">
                <i class="fas fa-plus mr-1"></i> Tambah Misi
            </button>
        </div>
    </div>

    <!-- Visi Card -->
    <div class="col-md-5 mb-4">
        <div class="glass p-6 rounded-2xl h-full border border-white/20 shadow-xl relative group">
            <h2 class="text-xl font-bold text-indigo-700 dark:text-indigo-300 mb-4 flex items-center">
                <i class="fas fa-eye mr-2"></i> Visi
            </h2>
            <div class="text-lg leading-relaxed text-gray-800 dark:text-gray-200">
                {{ $visi->content ?? 'Visi belum ditetapkan.' }}
            </div>
            @if($visi)
                <button onclick="openEditModal({{ $visi->id }}, {{ json_encode($visi->content) }})" 
                        class="absolute top-4 right-4 bg-white/10 hover:bg-white/20 p-2 rounded-lg transition-all text-indigo-400 shadow-sm border border-white/10">
                    <i class="fas fa-edit"></i>
                </button>
            @endif
        </div>
    </div>

    <!-- Misi Card -->
    <div class="col-md-7 mb-4">
        <div class="glass p-6 rounded-2xl h-full border border-white/20 shadow-xl">
            <h2 class="text-xl font-bold text-indigo-700 dark:text-indigo-300 mb-4 flex items-center">
                <i class="fas fa-bullseye mr-2"></i> Misi
            </h2>
            <ul class="space-y-3 text-gray-800 dark:text-gray-200">
                @if($misi->count() > 0)
                    @foreach($misi as $item)
                        <li class="flex items-start group relative pr-10">
                            <span class="inline-flex items-center justify-center bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 rounded-full h-6 w-6 text-xs font-bold mr-3 mt-1 shrink-0">
                                {{ $loop->iteration }}
                            </span>
                            <span class="flex-1">{{ $item->content }}</span>
                            
                            <div class="flex items-center gap-3 ml-2 shrink-0">
                                <button onclick="openEditModal({{ $item->id }}, {{ json_encode($item->content) }})" class="text-blue-400 hover:text-blue-500 transition-colors p-1">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('strategic.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus misi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-500 transition-colors p-1">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                @else
                    <li>Misi belum ditetapkan.</li>
                @endif
            </ul>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="glass w-full max-w-lg rounded-3xl border border-white/20 shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center bg-indigo-500/10">
            <h3 class="text-lg font-bold text-white">Edit Data</h3>
            <button onclick="closeModal('editModal')" class="text-white/50 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            <textarea name="content" id="editContent" rows="4" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('editModal')" class="px-6 py-2 rounded-xl bg-white/5 text-white text-sm font-bold border border-white/10">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-xl bg-indigo-500 text-white text-sm font-bold">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Add Modal --}}
<div id="addModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="glass w-full max-w-lg rounded-3xl border border-white/20 shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center bg-indigo-500/10">
            <h3 id="addTitle" class="text-lg font-bold text-white">Tambah Data</h3>
            <button onclick="closeModal('addModal')" class="text-white/50 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('strategic.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="type" id="addType">
            <textarea name="content" rows="4" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Masukkan konten..."></textarea>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('addModal')" class="px-6 py-2 rounded-xl bg-white/5 text-white text-sm font-bold border border-white/10">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-xl bg-indigo-500 text-white text-sm font-bold">Tambah</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditModal(id, content) {
        document.getElementById('editForm').action = `/strategic/${id}`;
        document.getElementById('editContent').value = content;
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }

    function openVisiModal() {
        document.getElementById('addTitle').innerText = 'Tambah Visi';
        document.getElementById('addType').value = 'visi';
        document.getElementById('addModal').classList.remove('hidden');
        document.getElementById('addModal').classList.add('flex');
    }

    function openMisiModal() {
        document.getElementById('addTitle').innerText = 'Tambah Misi';
        document.getElementById('addType').value = 'misi';
        document.getElementById('addModal').classList.remove('hidden');
        document.getElementById('addModal').classList.add('flex');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.getElementById(id).classList.remove('flex');
    }
</script>
@endpush
@endsection

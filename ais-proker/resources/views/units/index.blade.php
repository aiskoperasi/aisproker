@extends('layouts.app')

@section('title', 'Manajemen Unit')
@section('page-title', 'Manajemen Unit')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-indigo-400">Daftar Unit Organisasi</h2>
            <p class="text-sm text-theme-muted">Kelola unit KBM dan Supporting dalam ekosistem AIS.</p>
        </div>
        <button onclick="openCreateModal()" class="bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-bold px-4 py-2.5 rounded-xl transition-all shadow-lg shadow-indigo-500/25 flex items-center gap-2 w-fit">
            <i class="fas fa-plus"></i> Tambah Unit
        </button>
    </div>

    {{-- Table --}}
    <div class="glass rounded-2xl border border-white/10 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-gray-300 dark:border-gray-700 text-xs uppercase tracking-widest text-theme-muted bg-gray-50/50 dark:bg-transparent">
                        <th class="px-6 py-4 font-black">Nama Unit</th>
                        <th class="px-6 py-4 font-black">Tipe</th>
                        <th class="px-6 py-4 font-black">Deskripsi</th>
                        <th class="px-6 py-4 font-black text-center">User</th>
                        <th class="px-6 py-4 font-black text-center">Program</th>
                        <th class="px-6 py-4 font-black text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
                    @forelse($units as $unit)
                    <tr class="hover:bg-white/40 dark:hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4 font-bold text-theme-main">{{ $unit->name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase {{ $unit->type === 'KBM' ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400' : 'bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400' }}">
                                {{ $unit->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-theme-muted text-xs">{{ $unit->description ?: '—' }}</td>
                        <td class="px-6 py-4 text-center font-bold text-theme-main">{{ $unit->users_count }}</td>
                        <td class="px-6 py-4 text-center font-bold text-indigo-400">{{ $unit->work_programs_count }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditModal({{ $unit->toJson() }})" class="p-2 text-blue-400 hover:bg-blue-500/10 rounded-lg transition-all" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('units.destroy', $unit->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-400 hover:bg-red-500/10 rounded-lg transition-all" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-theme-muted italic">Belum ada data unit.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Create/Edit Modal --}}
<div id="unitModal" class="fixed inset-0 z-[250] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="glass w-full max-w-lg rounded-3xl border border-white/20 shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center bg-indigo-500/10">
            <h3 id="modalTitle" class="text-lg font-bold text-white">Tambah Unit</h3>
            <button onclick="closeModal()" class="text-white/50 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <form id="unitForm" method="POST" class="p-6 space-y-4">
            @csrf
            <div id="methodField"></div>
            
            <div>
                <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Nama Unit</label>
                <input type="text" name="name" id="unit_name" required class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
            </div>

            <div>
                <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Tipe Unit</label>
                <select name="type" id="unit_type" required class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                    <option value="KBM">KBM (Akademik)</option>
                    <option value="Supporting">Supporting (Manajemen/Fasilitas)</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Deskripsi</label>
                <textarea name="description" id="unit_description" rows="3" class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal()" class="px-6 py-2.5 rounded-xl bg-white/5 text-white text-sm font-bold border border-white/10">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-bold shadow-lg shadow-indigo-500/20 transition-all hover:bg-indigo-500">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openCreateModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Unit';
        document.getElementById('unitForm').action = "{{ route('units.store') }}";
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('unit_name').value = '';
        document.getElementById('unit_type').value = 'KBM';
        document.getElementById('unit_description').value = '';
        document.getElementById('unitModal').classList.replace('hidden', 'flex');
    }

    function openEditModal(unit) {
        document.getElementById('modalTitle').innerText = 'Edit Unit';
        document.getElementById('unitForm').action = `/units/${unit.id}`;
        document.getElementById('methodField').innerHTML = '@method("PUT")';
        document.getElementById('unit_name').value = unit.name;
        document.getElementById('unit_type').value = unit.type;
        document.getElementById('unit_description').value = unit.description || '';
        document.getElementById('unitModal').classList.replace('hidden', 'flex');
    }

    function closeModal() {
        document.getElementById('unitModal').classList.replace('flex', 'hidden');
    }
</script>
@endpush
@endsection

@extends('layouts.app')

@section('title', 'Kelola Program Induk')
@section('page-title', 'Kelola Program Induk')

@section('content')
<div class="space-y-6">
    <div class="glass p-6 rounded-2xl border border-white/10 shadow-xl overflow-hidden">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-indigo-400 flex items-center gap-2">
                <i class="fas fa-folder-tree"></i> Daftar Program Induk
            </h2>
            <button onclick="openCreateModal()" class="bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-bold px-4 py-2.5 rounded-xl transition-all shadow-lg shadow-indigo-500/25 flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Program Induk
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-gray-300 dark:border-gray-700 text-xs uppercase tracking-widest text-theme-muted bg-gray-50/50 dark:bg-transparent">
                        <th class="px-6 py-4 font-black">No</th>
                        <th class="px-6 py-4 font-black">Nama Program</th>
                        <th class="px-6 py-4 font-black">Deskripsi</th>
                        <th class="px-6 py-4 font-black text-center">Jumlah Kegiatan</th>
                        <th class="px-6 py-4 font-black text-center w-32">Urutan</th>
                        <th class="px-6 py-4 font-black text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
                    @forelse($parentPrograms as $index => $program)
                    <tr class="hover:bg-white/40 dark:hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4 text-theme-muted">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-bold text-theme-main">{{ $program->name }}</td>
                        <td class="px-6 py-4 text-theme-muted italic text-xs">{{ Str::limit($program->description, 50) ?: '—' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-indigo-500/10 text-indigo-500 rounded-lg font-bold">
                                {{ $program->work_programs_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-theme-main">{{ $program->order }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditModal({{ json_encode($program) }})"
                                        class="inline-flex items-center gap-1 bg-blue-500/10 hover:bg-blue-500/20 text-blue-500 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form action="{{ route('parent-programs.destroy', $program->id) }}" method="POST" onsubmit="return confirm('Hapus program induk ini? Semua kegiatan di dalamnya akan kehilangan relasi induknya.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 bg-red-500/10 hover:bg-red-500/20 text-red-500 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center text-theme-muted italic">
                            <i class="fas fa-inbox text-4xl mb-3 block opacity-30"></i>
                            Belum ada program induk yang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Create Modal --}}
<div id="create_modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="glass w-full max-w-lg rounded-3xl border border-white/20 shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center bg-indigo-500/10">
            <h3 class="text-lg font-bold text-white">Tambah Program Induk</h3>
            <button onclick="closeCreateModal()" class="text-white/50 hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="{{ route('parent-programs.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Nama Program</label>
                <input type="text" name="name" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div>
                <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
            </div>
            <div>
                <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Urutan Tampil</label>
                <input type="number" name="order" value="0" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeCreateModal()" class="px-6 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white text-sm font-bold">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-500 text-white text-sm font-bold shadow-lg shadow-indigo-500/20">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div id="edit_modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="glass w-full max-w-lg rounded-3xl border border-white/20 shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center bg-indigo-500/10">
            <h3 class="text-lg font-bold text-white">Edit Program Induk</h3>
            <button onclick="closeEditModal()" class="text-white/50 hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="edit_form" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Nama Program</label>
                <input type="text" name="name" id="edit_name" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div>
                <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Deskripsi</label>
                <textarea name="description" id="edit_description" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
            </div>
            <div>
                <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Urutan Tampil</label>
                <input type="number" name="order" id="edit_order" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeEditModal()" class="px-6 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white text-sm font-bold">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-500 text-white text-sm font-bold shadow-lg shadow-indigo-500/20">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openCreateModal() {
        document.getElementById('create_modal').classList.remove('hidden');
        document.getElementById('create_modal').classList.add('flex');
    }
    function closeCreateModal() {
        document.getElementById('create_modal').classList.add('hidden');
        document.getElementById('create_modal').classList.remove('flex');
    }
    function openEditModal(program) {
        document.getElementById('edit_form').action = `{{ url('parent-programs') }}/${program.id}`;
        document.getElementById('edit_name').value = program.name;
        document.getElementById('edit_description').value = program.description || '';
        document.getElementById('edit_order').value = program.order;
        document.getElementById('edit_modal').classList.remove('hidden');
        document.getElementById('edit_modal').classList.add('flex');
    }
    function closeEditModal() {
        document.getElementById('edit_modal').classList.add('hidden');
        document.getElementById('edit_modal').classList.remove('flex');
    }
</script>
@endpush
@endsection

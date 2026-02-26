@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-indigo-400">Daftar Pengguna Sistem</h2>
            <p class="text-sm text-theme-muted">Kelola akun dan penugasan unit untuk setiap pengguna.</p>
        </div>
        <button onclick="openCreateModal()" class="bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-bold px-4 py-2.5 rounded-xl transition-all shadow-lg shadow-indigo-500/25 flex items-center gap-2 w-fit">
            <i class="fas fa-user-plus"></i> Tambah Pengguna
        </button>
    </div>

    {{-- Error handling --}}
    @if($errors->any())
    <div class="bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-xl text-sm">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Table --}}
    <div class="glass rounded-2xl border border-white/10 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-gray-300 dark:border-gray-700 text-xs uppercase tracking-widest text-theme-muted bg-gray-50/50 dark:bg-transparent">
                        <th class="px-6 py-4 font-black">Nama Pengguna</th>
                        <th class="px-6 py-4 font-black">Email</th>
                        <th class="px-6 py-4 font-black">Unit Kerja</th>
                        <th class="px-6 py-4 font-black text-center">Role</th>
                        <th class="px-6 py-4 font-black text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
                    @forelse($users as $user)
                    <tr class="hover:bg-white/40 dark:hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-500 font-bold uppercase text-xs">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <span class="font-bold text-theme-main">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-theme-muted">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @if($user->unit)
                                <span class="text-indigo-400 font-bold uppercase text-[10px] tracking-widest">{{ $user->unit->name }}</span>
                            @else
                                <span class="text-emerald-400 font-bold uppercase text-[10px] tracking-widest">Global / Admin</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase {{ $user->unit_id === null ? 'bg-indigo-500 text-white' : 'bg-gray-100 dark:bg-white/5 text-theme-muted' }}">
                                {{ $user->unit_id === null ? 'Super Admin' : 'Unit User' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditModal({{ $user->toJson() }})" class="p-2 text-blue-400 hover:bg-blue-500/10 rounded-lg transition-all" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-400 hover:bg-red-500/10 rounded-lg transition-all" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-theme-muted italic">Belum ada data pengguna.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Create/Edit Modal --}}
<div id="userModal" class="fixed inset-0 z-[250] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="glass w-full max-w-lg rounded-3xl border border-white/20 shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center bg-indigo-500/10">
            <h3 id="userModalTitle" class="text-lg font-bold text-white">Tambah Pengguna</h3>
            <button onclick="closeUserModal()" class="text-white/50 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <form id="userForm" method="POST" class="p-6 space-y-4">
            @csrf
            <div id="userMethodField"></div>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="user_name" required class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                </div>

                <div class="col-span-2">
                    <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Alamat Email</label>
                    <input type="email" name="email" id="user_email" required class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                </div>

                <div class="col-span-2">
                    <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Unit Kerja</label>
                    <select name="unit_id" id="user_unit_id" class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        <option value="">Global (Administrator)</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->type }})</option>
                        @endforeach
                    </select>
                </div>

                <div id="passwordGroup" class="col-span-2 grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Password</label>
                        <input type="password" name="password" id="user_password" class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        <p id="passwordHint" class="hidden text-[9px] text-theme-muted mt-1">Biarkan kosong jika tidak ingin mengubah password.</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="user_password_confirmation" class="w-full bg-white/5 dark:bg-slate-900 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-theme-main focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeUserModal()" class="px-6 py-2.5 rounded-xl bg-white/5 text-white text-sm font-bold border border-white/10">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-bold shadow-lg shadow-indigo-500/20 transition-all hover:bg-indigo-500">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openCreateModal() {
        document.getElementById('userModalTitle').innerText = 'Tambah Pengguna';
        document.getElementById('userForm').action = "{{ route('users.store') }}";
        document.getElementById('userMethodField').innerHTML = '';
        document.getElementById('user_name').value = '';
        document.getElementById('user_email').value = '';
        document.getElementById('user_unit_id').value = '';
        document.getElementById('user_password').required = true;
        document.getElementById('user_password_confirmation').required = true;
        document.getElementById('passwordHint').classList.add('hidden');
        document.getElementById('userModal').classList.replace('hidden', 'flex');
    }

    function openEditModal(user) {
        document.getElementById('userModalTitle').innerText = 'Edit Pengguna';
        document.getElementById('userForm').action = `/users/${user.id}`;
        document.getElementById('userMethodField').innerHTML = '@method("PUT")';
        document.getElementById('user_name').value = user.name;
        document.getElementById('user_email').value = user.email;
        document.getElementById('user_unit_id').value = user.unit_id || '';
        document.getElementById('user_password').required = false;
        document.getElementById('user_password_confirmation').required = false;
        document.getElementById('passwordHint').classList.remove('hidden');
        document.getElementById('userModal').classList.replace('hidden', 'flex');
    }

    function closeUserModal() {
        document.getElementById('userModal').classList.replace('flex', 'hidden');
    }
</script>
@endpush
@endsection

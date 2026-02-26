@extends('layouts.app')

@section('title', 'Tambah Item Anggaran')
@section('page-title', 'Tambah Item Anggaran')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <a href="{{ route('budget.index') }}"
       class="inline-flex items-center gap-2 text-sm text-theme-muted hover:text-indigo-400 transition-colors">
        <i class="fas fa-arrow-left"></i> Kembali ke APBS
    </a>

    <div class="glass rounded-2xl border border-white/10 p-6 shadow-xl">
        <h2 class="text-lg font-bold text-indigo-400 flex items-center gap-2 mb-6">
            <i class="fas fa-plus-circle"></i> Tambah Item Anggaran Baru
        </h2>

        <form action="{{ route('budget.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Kode --}}
            <div>
                <label class="text-sm font-semibold text-theme-main block mb-2">
                    Kode Anggaran <span class="text-red-400">*</span>
                </label>
                <input type="text" name="code" value="{{ old('code') }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 text-theme-main text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none @error('code') border-red-400 @enderror"
                       placeholder="Contoh: 5.01.01" required>
                @error('code')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Keterangan --}}
            <div>
                <label class="text-sm font-semibold text-theme-main block mb-2">
                    Keterangan / Item Anggaran <span class="text-red-400">*</span>
                </label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 text-theme-main text-sm resize-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none @error('description') border-red-400 @enderror"
                          required>{{ old('description') }}</textarea>
                @error('description')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pagu & Realisasi --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold text-theme-main block mb-2">
                        Pagu Anggaran (Rp) <span class="text-red-400">*</span>
                    </label>
                    <input type="number" name="amount" value="{{ old('amount') }}"
                           min="0" step="any"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 text-theme-main text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none @error('amount') border-red-400 @enderror"
                           required>
                    @error('amount')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-theme-main block mb-2">
                        Realisasi (Rp)
                    </label>
                    <input type="number" name="realization" value="{{ old('realization', 0) }}"
                           min="0" step="any"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 text-theme-main text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none @error('realization') border-red-400 @enderror">
                    @error('realization')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Catatan --}}
            <div>
                <label class="text-sm font-semibold text-theme-main block mb-2">Catatan</label>
                <input type="text" name="notes" value="{{ old('notes') }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 text-theme-main text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none"
                       placeholder="Catatan tambahan (opsional)">
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-indigo-500/25 flex items-center justify-center gap-2">
                    <i class="fas fa-plus"></i> Simpan Item Anggaran
                </button>
                <a href="{{ route('budget.index') }}"
                   class="px-5 py-3 rounded-xl border border-gray-300 dark:border-gray-600 text-theme-muted hover:text-theme-main text-sm font-semibold transition-all flex items-center gap-2">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection

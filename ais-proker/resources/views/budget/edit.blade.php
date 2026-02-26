@extends('layouts.app')

@section('title', 'Edit Item Anggaran')
@section('page-title', 'Edit Item Anggaran')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <a href="{{ route('budget.index') }}"
       class="inline-flex items-center gap-2 text-sm text-theme-muted hover:text-indigo-400 transition-colors">
        <i class="fas fa-arrow-left"></i> Kembali ke APBS
    </a>

    <div class="glass rounded-2xl border border-white/10 p-6 shadow-xl">
        <h2 class="text-lg font-bold text-indigo-400 flex items-center gap-2 mb-6">
            <i class="fas fa-edit"></i> Edit Item Anggaran
        </h2>

        <form action="{{ route('budget.update', $budget->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Kode --}}
            <div>
                <label class="text-sm font-semibold text-theme-main block mb-2">
                    Kode Anggaran <span class="text-red-400">*</span>
                </label>
                <input type="text" name="code" value="{{ old('code', $budget->code) }}"
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
                          required>{{ old('description', $budget->description) }}</textarea>
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
                    <input type="number" name="amount" value="{{ old('amount', $budget->amount) }}"
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
                    <input type="number" name="realization" value="{{ old('realization', $budget->realization) }}"
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
                <input type="text" name="notes" value="{{ old('notes', $budget->notes) }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 text-theme-main text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none"
                       placeholder="Catatan tambahan (opsional)">
            </div>

            {{-- Preview serapan --}}
            @php
                $pct = $budget->amount > 0 ? round(($budget->realization / $budget->amount) * 100, 1) : 0;
                $sisa = $budget->amount - $budget->realization;
            @endphp
            <div class="bg-indigo-500/5 border border-indigo-500/10 rounded-xl p-4">
                <p class="text-xs font-bold text-indigo-400 uppercase tracking-wider mb-3">Preview Saat Ini</p>
                <div class="grid grid-cols-3 gap-3 text-center text-xs mb-3">
                    <div>
                        <p class="text-theme-muted">Pagu</p>
                        <p class="font-black text-theme-main font-mono">Rp {{ number_format($budget->amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-theme-muted">Realisasi</p>
                        <p class="font-black text-emerald-400 font-mono">Rp {{ number_format($budget->realization, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-theme-muted">Sisa</p>
                        <p class="font-black text-rose-400 font-mono">Rp {{ number_format($sisa, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                    <div class="h-2 rounded-full bg-emerald-500" style="width: {{ min($pct, 100) }}%"></div>
                </div>
                <p class="text-center text-xs font-bold text-emerald-400 mt-1">{{ $pct }}% Terserap</p>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-indigo-500/25 flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('budget.index') }}"
                   class="px-5 py-3 rounded-xl border border-gray-300 dark:border-gray-600 text-theme-muted hover:text-theme-main text-sm font-semibold transition-all flex items-center gap-2">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>

    {{-- Danger Zone --}}
    <div class="glass rounded-2xl border border-red-500/20 p-5">
        <h3 class="text-sm font-bold text-red-400 mb-3 flex items-center gap-2">
            <i class="fas fa-triangle-exclamation"></i> Hapus Item
        </h3>
        <p class="text-xs text-theme-muted mb-3">Menghapus item anggaran ini secara permanen dan tidak dapat dikembalikan.</p>
        <form action="{{ route('budget.destroy', $budget->id) }}" method="POST"
              onsubmit="return confirm('Yakin ingin menghapus item anggaran ini?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 font-semibold px-4 py-2 rounded-lg text-sm transition-all">
                <i class="fas fa-trash mr-2"></i> Hapus Item Ini
            </button>
        </form>
    </div>

</div>
@endsection

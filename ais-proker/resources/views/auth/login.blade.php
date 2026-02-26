@extends('layouts.guest')

@section('title', 'Login - AIS Program Kerja')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6 bg-[var(--color-dark-bg)] transition-colors duration-300 relative overflow-hidden">
    
    <!-- Background Accents -->
    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-indigo-500/10 to-transparent pointer-events-none"></div>
    <div class="absolute -top-20 -right-20 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Theme Toggle (Fixed Top Right) -->
    <button @click="toggleTheme()" class="fixed top-6 right-6 w-12 h-12 rounded-full bg-[var(--color-dark-card)] border border-theme text-theme-muted hover:text-indigo-500 transition-all shadow-xl z-[100] flex items-center justify-center pointer-events-auto">
        <i class="fas text-lg" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
    </button>

    <div class="w-full max-w-md bg-[var(--color-dark-card)] rounded-2xl shadow-xl border border-theme overflow-hidden relative z-10 transition-colors duration-300">
        <!-- Header -->
        <div class="p-8 pb-6 text-center border-b border-theme bg-white/5">
            <div class="w-20 h-20 mx-auto rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20 mb-4 overflow-hidden bg-white/5 border border-theme">
                <img src="{{ asset('images/logo-annahl.png') }}" alt="Logo" class="w-full h-full object-contain p-2">
            </div>
            
            <h2 class="text-xl font-bold text-theme-main tracking-tight uppercase">
                <span class="text-indigo-400">AIS</span> PROGRAM KERJA
            </h2>
            <p class="text-theme-muted text-[10px] mt-1 uppercase tracking-widest font-black opacity-60">Sistem Perencanaan Terpadu</p>
        </div>

        <!-- Form -->
        <div class="p-8">
            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                    <div class="text-sm text-red-500">
                        @foreach($errors->all() as $error)
                            <p class="font-bold">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="space-y-1.5">
                    <label class="text-xs font-black text-theme-main uppercase tracking-widest opacity-70">Email Administrator</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-3.5 text-gray-400"></i>
                        <input type="email" name="email" class="w-full bg-[var(--color-dark-bg)] border border-theme rounded-xl py-3 pl-11 pr-4 text-theme-main placeholder-gray-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors" placeholder="admin@annahl.sch.id" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="space-y-1.5" x-data="{ showPassword: false }">
                    <label class="text-xs font-black text-theme-main uppercase tracking-widest opacity-70">Kata Sandi</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-3.5 text-gray-400"></i>
                        <input :type="showPassword ? 'text' : 'password'" name="password" class="w-full bg-[var(--color-dark-bg)] border border-theme rounded-xl py-3 pl-11 pr-12 text-theme-main placeholder-gray-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors" placeholder="••••••••" required>
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-3.5 text-gray-400 hover:text-indigo-500">
                            <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between py-1">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 bg-[var(--color-dark-bg)] border-theme">
                        <span class="text-xs text-theme-muted group-hover:text-indigo-400 transition-colors">Ingat sesi saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-400 text-white rounded-xl py-3 font-bold shadow-lg shadow-indigo-500/20 transition-all duration-200 transform hover:-translate-y-0.5">
                    <i class="fas fa-sign-in-alt mr-2"></i> MASUK APLIKASI
                </button>
            </form>
        </div>
        
        <div class="p-6 border-t border-theme text-center bg-[var(--color-dark-bg)]/50">
            <a href="https://ais-umum.ametriyadhi.com" class="group text-[9px] text-theme-muted hover:text-indigo-400 transition-all font-black uppercase tracking-[0.2em] inline-flex items-center justify-center gap-2 mb-2">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                <span>Kembali ke Portal AIS</span>
            </a>
            <p class="text-[9px] text-theme-muted/40 font-bold uppercase tracking-[0.4em]">&copy; {{ date('Y') }} AN NAHL ISLAMIC SCHOOL</p>
        </div>
    </div>
</div>

<style>
    .animate-shake {
        animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
    }
    @keyframes shake {
        10%, 90% { transform: translate3d(-1px, 0, 0); }
        20%, 80% { transform: translate3d(2px, 0, 0); }
        30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
        40%, 60% { transform: translate3d(4px, 0, 0); }
    }
    .glass {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }
    .dark .glass {
        background: rgba(0, 0, 0, 0.2);
    }
</style>
@endsection

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AIS Program Kerja')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        indigo: {
                            50: '#E6F7FF',
                            100: '#BAE7FF',
                            200: '#91D5FF',
                            300: '#69C0FF',
                            400: '#40A9FF',
                            500: '#0066FF',
                            600: '#0052CC',
                            700: '#0047B3',
                            800: '#003A8C',
                            900: '#002766',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Prevent Dark Mode Flashing --}}
    <script>
        if (localStorage.getItem('darkMode') === 'false') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>
    
    @stack('styles')
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-[var(--color-dark-bg)] text-theme-main transition-colors duration-300"
      x-data="{
    sidebarOpen: localStorage.getItem('sidebarOpen') === 'false' ? false : true,
    darkMode: localStorage.getItem('darkMode') === 'false' ? false : true,
    mobileMenuOpen: false,
    toggleSidebar() {
        this.sidebarOpen = !this.sidebarOpen;
        localStorage.setItem('sidebarOpen', this.sidebarOpen);
    },
    toggleTheme() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
}">

    <div class="flex min-h-screen md:h-screen md:overflow-hidden">

        {{-- ===== SIDEBAR (Desktop) ===== --}}
        <aside class="hidden md:flex flex-shrink-0 bg-[var(--color-dark-card)] border-r border-theme flex-col transition-all duration-300 relative"
               :class="sidebarOpen ? 'w-64' : 'w-20'">

            {{-- Toggle Button --}}
            <button @click="toggleSidebar()" class="absolute -right-3 top-8 w-6 h-6 bg-indigo-600 rounded-full text-white flex items-center justify-center shadow-lg hover:bg-indigo-500 transition-colors z-50 border border-[var(--color-dark-bg)]">
                <i class="fas fa-chevron-left text-xs transition-transform duration-300" :class="!sidebarOpen ? 'rotate-180' : ''"></i>
            </button>

            {{-- Logo --}}
            <div class="h-16 flex items-center px-6 border-b border-theme overflow-hidden whitespace-nowrap">
                <div class="flex items-center gap-3 text-theme-main font-semibold text-lg tracking-wide">
                    <div class="w-8 h-8 flex-shrink-0 rounded-lg bg-white dark:bg-white/10 flex items-center justify-center shadow-lg shadow-indigo-500/10 text-white overflow-hidden border border-theme/50 p-1">
                        <img src="{{ asset('images/logo-annahl.png') }}" class="w-full h-full object-contain" alt="Logo Annahl">
                    </div>
                    <span x-show="sidebarOpen" x-transition.opacity.duration.200ms>AIS Program Kerja</span>
                </div>
            </div>

            {{-- Navigation --}}
            <div class="flex-1 overflow-y-auto overflow-x-hidden py-4 custom-scrollbar">
                <nav class="px-3 space-y-1">
                    <p class="px-3 text-xs font-medium text-gray-500 uppercase tracking-wider mb-2 mt-2 h-4"
                        :class="!sidebarOpen ? 'opacity-0' : 'opacity-100'">
                        <span x-show="sidebarOpen">Program & Strategi</span>
                    </p>

                    <a href="{{ route('dashboard') }}"
                       class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-500/10 text-indigo-500' : 'text-theme-muted hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-white/5' }}"
                       title="Dashboard">
                        <i class="fas fa-gauge w-5 h-5 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-indigo-500' }}"
                           :class="!sidebarOpen ? 'mx-auto' : 'mr-3'"></i>
                        <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Dashboard</span>
                    </a>

                    <a href="{{ route('strategic.index') }}"
                       class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('strategic.*') ? 'bg-indigo-500/10 text-indigo-500' : 'text-theme-muted hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-white/5' }}"
                       title="Visi & Misi">
                        <i class="fas fa-eye w-5 h-5 flex-shrink-0 {{ request()->routeIs('strategic.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-indigo-500' }}"
                           :class="!sidebarOpen ? 'mx-auto' : 'mr-3'"></i>
                        <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Visi & Misi</span>
                    </a>

                    <a href="{{ route('quality-targets.index') }}"
                       class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('quality-targets.*') ? 'bg-indigo-500/10 text-indigo-500' : 'text-theme-muted hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-white/5' }}"
                       title="Sasaran Mutu">
                        <i class="fas fa-chart-pie w-5 h-5 flex-shrink-0 {{ request()->routeIs('quality-targets.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-indigo-500' }}"
                           :class="!sidebarOpen ? 'mx-auto' : 'mr-3'"></i>
                        <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Sasaran Mutu</span>
                    </a>

                    <a href="{{ route('work-programs.main') }}"
                       class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('work-programs.main') ? 'bg-indigo-500/10 text-indigo-500' : 'text-theme-muted hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-white/5' }}"
                       title="Ikhtisar Program">
                        <i class="fas fa-layer-group w-5 h-5 flex-shrink-0 {{ request()->routeIs('work-programs.main') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-indigo-500' }}"
                           :class="!sidebarOpen ? 'mx-auto' : 'mr-3'"></i>
                        <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Ikhtisar Program</span>
                    </a>

                    <a href="{{ route('work-programs.index') }}"
                       class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('work-programs.index') ? 'bg-indigo-500/10 text-indigo-500' : 'text-theme-muted hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-white/5' }}"
                       title="Program Kerja">
                        <i class="fas fa-tasks w-5 h-5 flex-shrink-0 {{ request()->routeIs('work-programs.index') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-indigo-500' }}"
                           :class="!sidebarOpen ? 'mx-auto' : 'mr-3'"></i>
                        <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Program Kerja</span>
                    </a>

                    <a href="{{ route('parent-programs.index') }}"
                       class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('parent-programs.index') ? 'bg-indigo-500/10 text-indigo-500' : 'text-theme-muted hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-white/5' }}"
                       title="Kelola Program Induk">
                        <i class="fas fa-folder-tree w-5 h-5 flex-shrink-0 {{ request()->routeIs('parent-programs.index') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-indigo-500' }}"
                           :class="!sidebarOpen ? 'mx-auto' : 'mr-3'"></i>
                        <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Kelola Program Induk</span>
                    </a>

                    <a href="{{ route('budget.index') }}"
                       class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('budget.*') ? 'bg-indigo-500/10 text-indigo-500' : 'text-theme-muted hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-white/5' }}"
                       title="APBS (Anggaran)">
                        <i class="fas fa-file-invoice-dollar w-5 h-5 flex-shrink-0 {{ request()->routeIs('budget.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-indigo-500' }}"
                           :class="!sidebarOpen ? 'mx-auto' : 'mr-3'"></i>
                        <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Anggaran (APBS)</span>
                    </a>

                    <a href="{{ route('reports.index') }}"
                       class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('reports.index') ? 'bg-emerald-500/10 text-emerald-500' : 'text-theme-muted hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-white/5' }}"
                       title="Pusat Laporan">
                        <i class="fas fa-file-medical w-5 h-5 flex-shrink-0 {{ request()->routeIs('reports.index') ? 'text-emerald-500' : 'text-gray-400 group-hover:text-emerald-500' }}"
                           :class="!sidebarOpen ? 'mx-auto' : 'mr-3'"></i>
                        <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap font-bold">Pusat Laporan</span>
                    </a>

                    {{-- Management (Admin Only) --}}
                    @auth
                    @if(Auth::user()->unit_id === null)
                    <p class="px-3 text-xs font-medium text-gray-500 uppercase tracking-wider mb-2 mt-6 h-4"
                        :class="!sidebarOpen ? 'opacity-0' : 'opacity-100'">
                        <span x-show="sidebarOpen">Pengaturan Sistem</span>
                    </p>

                    <a href="{{ route('units.index') }}"
                       class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('units.*') ? 'bg-indigo-500/10 text-indigo-500' : 'text-theme-muted hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-white/5' }}"
                       title="Manajemen Unit">
                        <i class="fas fa-building w-5 h-5 flex-shrink-0 {{ request()->routeIs('units.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-indigo-500' }}"
                           :class="!sidebarOpen ? 'mx-auto' : 'mr-3'"></i>
                        <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap font-bold">Manajemen Unit</span>
                    </a>

                    <a href="{{ route('users.index') }}"
                       class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-indigo-500/10 text-indigo-500' : 'text-theme-muted hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-white/5' }}"
                       title="Manajemen User">
                        <i class="fas fa-users-cog w-5 h-5 flex-shrink-0 {{ request()->routeIs('users.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-indigo-500' }}"
                           :class="!sidebarOpen ? 'mx-auto' : 'mr-3'"></i>
                        <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap font-bold">Manajemen User</span>
                    </a>
                    @endif
                    @endauth

                    {{-- Ecosystem AIS --}}
                    <p class="px-3 text-xs font-medium text-gray-500 uppercase tracking-wider mb-2 mt-6 h-4"
                        :class="!sidebarOpen ? 'opacity-0' : 'opacity-100'">
                        <span x-show="sidebarOpen">Ecosystem AIS</span>
                    </p>

                    <a href="https://ais-umum.ametriyadhi.com" target="_blank" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 bg-indigo-600/5 text-indigo-500 border border-indigo-500/20 hover:bg-indigo-600/10 mb-2">
                        <i class="fas fa-building w-5 h-5 flex-shrink-0 mr-3 text-indigo-500" :class="!sidebarOpen ? 'mx-auto' : 'mr-3'"></i>
                        <span x-show="sidebarOpen" x-transition.opacity class="font-bold whitespace-nowrap">AIS-LAYANAN</span>
                    </a>

                    <a href="https://asset.annahl-islamic.sch.id" target="_blank" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 bg-emerald-600/5 text-emerald-500 border border-emerald-500/20 hover:bg-emerald-600/10 mb-2">
                        <i class="fas fa-boxes w-5 h-5 flex-shrink-0 text-emerald-500" :class="!sidebarOpen ? 'mx-auto' : 'mr-3'"></i>
                        <span x-show="sidebarOpen" x-transition.opacity class="font-bold whitespace-nowrap">AIS-ASSET</span>
                    </a>

                    <a href="https://ais-admin.ametriyadhi.com" target="_blank" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 bg-rose-600/5 text-rose-500 border border-rose-500/20 hover:bg-rose-600/10 mb-2">
                        <i class="fas fa-user-cog w-5 h-5 flex-shrink-0 text-rose-500" :class="!sidebarOpen ? 'mx-auto' : 'mr-3'"></i>
                        <span x-show="sidebarOpen" x-transition.opacity class="font-bold whitespace-nowrap">AIS-ADMIN</span>
                    </a>

                    <a href="https://ais-fms.ametriyadhi.com" target="_blank" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 bg-amber-600/5 text-amber-500 border border-amber-500/20 hover:bg-amber-600/10 mb-2">
                        <i class="fas fa-wallet w-5 h-5 flex-shrink-0 text-amber-500" :class="!sidebarOpen ? 'mx-auto' : 'mr-3'"></i>
                        <span x-show="sidebarOpen" x-transition.opacity class="font-bold whitespace-nowrap">AIS-FMS</span>
                    </a>

                    <a href="https://ais-security.ametriyadhi.com" target="_blank" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 bg-slate-600/5 text-slate-500 border border-slate-500/20 hover:bg-slate-600/10 mb-2">
                        <i class="fas fa-shield-halved w-5 h-5 flex-shrink-0 text-slate-500" :class="!sidebarOpen ? 'mx-auto' : 'mr-3'"></i>
                        <span x-show="sidebarOpen" x-transition.opacity class="font-bold whitespace-nowrap">AIS-SECURITY</span>
                    </a>
                </nav>
            </div>

            {{-- User Profile --}}
            <div class="p-4 border-t border-theme">
                @auth
                <div class="flex items-center gap-3 p-2 rounded-xl overflow-hidden group">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0 shadow-lg shadow-indigo-500/20">
                        <span class="uppercase">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0 transition-opacity duration-200" x-show="sidebarOpen" x-transition.opacity>
                        <p class="text-sm font-bold text-theme-main truncate leading-none mb-1">{{ Auth::user()->name }}</p>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-[10px] text-red-400 font-bold uppercase tracking-wider hover:text-red-300 transition-colors flex items-center gap-1">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <div class="flex items-center gap-3 p-2 rounded-xl overflow-hidden group">
                    <a href="{{ route('login') }}" class="text-sm font-bold text-indigo-500 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-sign-in-alt mr-1"></i> Login
                    </a>
                </div>
                @endauth
            </div>
        </aside>

        {{-- ===== MAIN CONTENT ===== --}}
        <div class="flex-1 flex flex-col overflow-hidden relative">

            {{-- Topbar --}}
            <header class="h-16 flex items-center justify-between px-6 border-b border-theme bg-[var(--color-dark-bg)]/80 backdrop-blur-md z-10">
                <div class="flex items-center gap-3">
                    {{-- Mobile logo --}}
                    <div class="md:hidden">
                        <div class="w-10 h-10 rounded-lg bg-white dark:bg-white/10 flex items-center justify-center shadow-lg shadow-indigo-500/10 text-white overflow-hidden border border-theme/50 p-1">
                            <img src="{{ asset('images/logo-annahl.png') }}" class="w-full h-full object-contain" alt="Logo Annahl">
                        </div>
                    </div>
                    <h1 class="text-xl font-semibold text-theme-main tracking-tight">@yield('page-title', 'AIS Program Kerja')</h1>
                </div>

                <div class="flex items-center gap-4">
                    {{-- Unit Filter (Admin) --}}
                    @auth
                    @if(Auth::user()->unit_id === null) {{-- Assuming null is admin/global --}}
                    <form action="{{ route('select-unit') }}" method="POST" class="hidden lg:block">
                        @csrf
                        <select name="unit_id" onchange="this.form.submit()" 
                                class="bg-[var(--color-dark-card)] border border-theme text-theme-main text-xs rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2">
                            <option value="">Semua Unit</option>
                            @foreach($allUnits as $unit)
                                <option value="{{ $unit->id }}" {{ session('unit_id') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    @endif
                    @endauth

                    {{-- School Year Selector --}}
                    <form action="{{ route('select-year') }}" method="POST" class="hidden sm:block">
                        @csrf
                        <select name="school_year_id" onchange="this.form.submit()" 
                                class="bg-[var(--color-dark-card)] border border-theme text-theme-main text-xs rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2">
                            @foreach($allSchoolYears as $year)
                                <option value="{{ $year->id }}" {{ session('school_year_id') == $year->id ? 'selected' : '' }}>
                                    TA {{ $year->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    {{-- Theme Toggle --}}
                    <button @click="toggleTheme()" class="p-2 text-theme-muted hover:text-indigo-500 transition-colors rounded-lg hover:bg-black/5 dark:hover:bg-white/5" title="Toggle Theme">
                        <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                    </button>

                    {{-- Mobile Hamburger --}}
                    <button @click="mobileMenuOpen = true" class="md:hidden p-2 text-theme-muted hover:text-indigo-500 transition-colors rounded-lg hover:bg-black/5 dark:hover:bg-white/5">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[var(--color-dark-bg)] p-6 relative">
                <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-indigo-500/5 to-transparent pointer-events-none" :class="darkMode ? 'opacity-100' : 'opacity-0'"></div>

                @if(session('success'))
                    <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl flex items-center gap-3">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl flex items-center gap-3">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl">
                        <div class="flex items-center gap-3 mb-2 font-bold whitespace-nowrap">
                            <i class="fas fa-circle-xmark"></i> Ada kesalahan input:
                        </div>
                        <ul class="list-disc list-inside text-sm space-y-1 opacity-90">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')

                <div class="h-24 md:hidden w-full"></div>
            </main>
        </div>
    </div>

    {{-- ===== MOBILE BOTTOM NAV ===== --}}
    <div class="md:hidden fixed bottom-0 left-0 right-0 h-16 bg-[var(--color-dark-card)] border-t border-theme flex items-center justify-around z-50 px-2">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-0.5 flex-1 {{ request()->routeIs('dashboard') ? 'text-indigo-500' : 'text-theme-muted' }}">
            <i class="fas fa-gauge text-xl"></i>
            <span class="text-[9px] font-medium">Dashboard</span>
        </a>
        <a href="{{ route('strategic.index') }}" class="flex flex-col items-center gap-0.5 flex-1 {{ request()->routeIs('strategic.*') ? 'text-indigo-500' : 'text-theme-muted' }}">
            <i class="fas fa-eye text-xl"></i>
            <span class="text-[9px] font-medium">Visi & Misi</span>
        </a>
        <a href="{{ route('quality-targets.index') }}" class="flex flex-col items-center gap-0.5 flex-1 {{ request()->routeIs('quality-targets.*') ? 'text-indigo-500' : 'text-theme-muted' }}">
            <i class="fas fa-chart-pie text-xl"></i>
            <span class="text-[9px] font-medium">Mutu</span>
        </a>
        <a href="{{ route('work-programs.index') }}" class="flex flex-col items-center gap-0.5 flex-1 {{ request()->routeIs('work-programs.index') ? 'text-indigo-500' : 'text-theme-muted' }}">
            <i class="fas fa-tasks text-xl"></i>
            <span class="text-[9px] font-medium">Program</span>
        </a>
        <a href="{{ route('budget.index') }}" class="flex flex-col items-center gap-0.5 flex-1 {{ request()->routeIs('budget.*') ? 'text-indigo-500' : 'text-theme-muted' }}">
            <i class="fas fa-file-invoice-dollar text-xl"></i>
            <span class="text-[9px] font-medium">APBS</span>
        </a>
    </div>

    {{-- ===== MOBILE SIDEBAR DRAWER ===== --}}
    <template x-teleport="body">
        <div x-show="mobileMenuOpen" class="fixed inset-0 z-[200] md:hidden" x-cloak>
            <div @click="mobileMenuOpen = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div x-show="mobileMenuOpen"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="fixed inset-y-0 right-0 w-80 max-w-[80vw] bg-[var(--color-dark-card)] shadow-2xl flex flex-col border-l border-theme">

                <div class="h-16 flex items-center justify-between px-6 border-b border-theme">
                    <span class="font-bold text-theme-main">AIS Program Kerja</span>
                    <button @click="mobileMenuOpen = false" class="p-2 text-theme-muted hover:text-theme-main">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
                    <p class="px-3 text-[10px] font-bold text-theme-muted uppercase tracking-widest mb-3">Program & Strategi</p>

                    <a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false"
                       class="flex items-center gap-4 px-4 py-3 rounded-2xl {{ request()->routeIs('dashboard') ? 'bg-indigo-500/10 text-indigo-500' : 'text-theme-muted hover:bg-white/5' }}">
                        <i class="fas fa-gauge w-5"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('strategic.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center gap-4 px-4 py-3 rounded-2xl {{ request()->routeIs('strategic.*') ? 'bg-indigo-500/10 text-indigo-500' : 'text-theme-muted hover:bg-white/5' }}">
                        <i class="fas fa-eye w-5"></i>
                        <span class="font-medium">Visi & Misi</span>
                    </a>

                    <a href="{{ route('quality-targets.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center gap-4 px-4 py-3 rounded-2xl {{ request()->routeIs('quality-targets.*') ? 'bg-indigo-500/10 text-indigo-500' : 'text-theme-muted hover:bg-white/5' }}">
                        <i class="fas fa-chart-pie w-5"></i>
                        <span class="font-medium">Sasaran Mutu</span>
                    </a>

                    <a href="{{ route('work-programs.main') }}" @click="mobileMenuOpen = false"
                       class="flex items-center gap-4 px-4 py-3 rounded-2xl {{ request()->routeIs('work-programs.main') ? 'bg-indigo-500/10 text-indigo-500' : 'text-theme-muted hover:bg-white/5' }}">
                        <i class="fas fa-layer-group w-5"></i>
                        <span class="font-medium">Ikhtisar Program</span>
                    </a>

                    <a href="{{ route('work-programs.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center gap-4 px-4 py-3 rounded-2xl {{ request()->routeIs('work-programs.index') ? 'bg-indigo-500/10 text-indigo-500' : 'text-theme-muted hover:bg-white/5' }}">
                        <i class="fas fa-tasks w-5"></i>
                        <span class="font-medium">Program Kerja</span>
                    </a>

                    <a href="{{ route('budget.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center gap-4 px-4 py-3 rounded-2xl {{ request()->routeIs('budget.*') ? 'bg-indigo-500/10 text-indigo-500' : 'text-theme-muted hover:bg-white/5' }}">
                        <i class="fas fa-file-invoice-dollar w-5"></i>
                        <span class="font-medium">Anggaran (APBS)</span>
                    </a>

                    <a href="{{ route('reports.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center gap-4 px-4 py-3 rounded-2xl {{ request()->routeIs('reports.index') ? 'bg-emerald-500/10 text-emerald-500' : 'text-theme-muted hover:bg-white/5' }}">
                        <i class="fas fa-file-medical w-5"></i>
                        <span class="font-bold">Pusat Laporan</span>
                    </a>

                    {{-- Management Mobile --}}
                    @auth
                    @if(Auth::user()->unit_id === null)
                    <div class="pt-4">
                        <p class="px-3 text-[10px] font-bold text-theme-muted uppercase tracking-widest mb-3">Pengaturan Sistem</p>
                    </div>

                    <a href="{{ route('units.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center gap-4 px-4 py-3 rounded-2xl {{ request()->routeIs('units.*') ? 'bg-indigo-500/10 text-indigo-500' : 'text-theme-muted hover:bg-white/5' }}">
                        <i class="fas fa-building w-5"></i>
                        <span class="font-medium">Manajemen Unit</span>
                    </a>

                    <a href="{{ route('users.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center gap-4 px-4 py-3 rounded-2xl {{ request()->routeIs('users.*') ? 'bg-indigo-500/10 text-indigo-500' : 'text-theme-muted hover:bg-white/5' }}">
                        <i class="fas fa-users-cog w-5"></i>
                        <span class="font-medium">Manajemen User</span>
                    </a>
                    @endif
                    @endauth

                    <div class="pt-4">
                        <p class="px-3 text-[10px] font-bold text-theme-muted uppercase tracking-widest mb-3">Ecosystem AIS</p>
                    </div>

                    <a href="https://ais-umum.ametriyadhi.com" target="_blank" class="flex items-center gap-4 px-4 py-3 rounded-2xl bg-indigo-500/10 text-indigo-500 border border-indigo-500/20">
                        <i class="fas fa-building w-5"></i>
                        <span class="font-bold">AIS-LAYANAN</span>
                    </a>

                    <a href="https://asset.annahl-islamic.sch.id" target="_blank" class="flex items-center gap-4 px-4 py-3 rounded-2xl bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                        <i class="fas fa-boxes w-5"></i>
                        <span class="font-bold">AIS-ASSET</span>
                    </a>

                    <a href="https://ais-admin.ametriyadhi.com" target="_blank" class="flex items-center gap-4 px-4 py-3 rounded-2xl bg-rose-500/10 text-rose-500 border border-rose-500/20">
                        <i class="fas fa-user-cog w-5"></i>
                        <span class="font-bold">AIS-ADMIN</span>
                    </a>

                    <a href="https://ais-fms.ametriyadhi.com" target="_blank" class="flex items-center gap-4 px-4 py-3 rounded-2xl bg-amber-500/10 text-amber-500 border border-amber-500/20">
                        <i class="fas fa-wallet w-5"></i>
                        <span class="font-bold">AIS-FMS</span>
                    </a>

                    <a href="https://ais-security.ametriyadhi.com" target="_blank" class="flex items-center gap-4 px-4 py-3 rounded-2xl bg-slate-500/10 text-slate-500 border border-slate-500/20">
                        <i class="fas fa-shield-halved w-5"></i>
                        <span class="font-bold">AIS-SECURITY</span>
                    </a>
                </div>

                <div class="p-6 border-t border-theme">
                    <button @click="toggleTheme(); mobileMenuOpen = false" class="flex items-center gap-4 w-full px-4 py-2 text-theme-muted">
                        <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                        <span class="font-medium" x-text="darkMode ? 'Mode Terang' : 'Mode Gelap'"></span>
                    </button>
                </div>
            </div>
        </div>
    </template>

    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>
</html>

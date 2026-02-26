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
</head>
<body class="font-sans antialiased bg-[var(--color-dark-bg)] text-theme-main transition-colors duration-300"
      x-data="{
    darkMode: localStorage.getItem('darkMode') === 'false' ? false : true,
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

    <div class="min-h-screen">
        @yield('content')
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</body>
</html>

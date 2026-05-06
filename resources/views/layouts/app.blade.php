<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MangoWMS') }}</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: #0f172a;
        }
        
        .sidebar-scroll {
            overflow-y: auto;
            scrollbar-width: thin;
        }
        
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar-scroll::-webkit-scrollbar-track {
            background: #1e293b;
        }
        
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #4f46e5;
            border-radius: 10px;
        }
        
        .main-scroll {
            overflow-y: auto;
            height: calc(100vh - 70px);
        }
        
        .main-scroll::-webkit-scrollbar {
            width: 6px;
        }
        
        .main-scroll::-webkit-scrollbar-track {
            background: #1e293b;
        }
        
        .main-scroll::-webkit-scrollbar-thumb {
            background: #4f46e5;
            border-radius: 10px;
        }
        
        .transition-sidebar {
            transition: all 0.3s ease;
        }
        
        /* Select2 Dark Mode */
        .select2-container--default .select2-selection--single {
            background-color: #0f172a !important;
            border-color: #334155 !important;
            color: #f1f5f9 !important;
            border-radius: 0.5rem !important;
            padding: 6px !important;
            height: 42px !important;
        }
        
        .select2-dropdown {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }
        
        .select2-search__field {
            background-color: #0f172a !important;
            border-color: #334155 !important;
            color: #f1f5f9 !important;
        }
        
        .select2-results__option {
            color: #cbd5e1 !important;
        }
        
        .select2-results__option--highlighted {
            background-color: #4f46e5 !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #64748b !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #f1f5f9 !important;
            line-height: 28px !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="flex h-screen">
        <!-- Sidebar -->
        @include('layouts.sidebar')
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navbar -->
            <header class="bg-slate-800 border-b border-slate-700 px-6 py-3 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <button id="sidebarToggle" class="lg:hidden text-gray-400 hover:text-white">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-white">@yield('title', 'Dashboard')</h1>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 text-gray-300 hover:text-white">
                            <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <span class="hidden md:block">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-slate-800 rounded-lg shadow-lg py-1 z-50 border border-slate-700" style="display: none;">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-slate-700">
                                <i class="fas fa-user mr-2"></i> Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-slate-700">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content Scroll -->
            <main class="main-scroll flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        });
    </script>
    
    @stack('scripts')
</body>
</html>
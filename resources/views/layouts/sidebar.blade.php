<aside id="sidebar" class="w-64 bg-slate-800 border-r border-slate-700 flex flex-col transition-sidebar -translate-x-full lg:translate-x-0 fixed lg:relative inset-y-0 left-0 z-30">
    <!-- Logo -->
    <div class="p-4 border-b border-slate-700">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <div class="w-9 h-9 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                <span class="text-white font-bold text-xl">M</span>
            </div>
            <span class="font-bold text-xl text-white">MangoWMS</span>
        </a>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="flex-1 py-4 overflow-y-auto sidebar-scroll">
        <div class="px-3 space-y-1">
            
            <!-- Dashboard - semua user -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-slate-700 hover:text-white transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : '' }}">
                <i class="fas fa-tachometer-alt w-5"></i>
                <span>Dashboard</span>
            </a>
            
            <!-- Products - semua user -->
            <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-slate-700 hover:text-white transition {{ request()->routeIs('products.*') ? 'bg-indigo-600 text-white' : '' }}">
                <i class="fas fa-box w-5"></i>
                <span>Products</span>
            </a>
            
            <!-- Batches - semua user -->
            <a href="{{ route('batches.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-slate-700 hover:text-white transition {{ request()->routeIs('batches.*') ? 'bg-indigo-600 text-white' : '' }}">
                <i class="fas fa-layer-group w-5"></i>
                <span>Batches</span>
            </a>
            
            <!-- Reports - Hanya untuk Manager, Director, Super Admin -->
            @auth
                @if(auth()->user()->hasAnyRole(['super_admin', 'director', 'warehouse_manager']))
                <div class="pt-4 mt-2 border-t border-slate-700">
                    <p class="text-xs text-gray-500 uppercase tracking-wider px-3 mb-2">Reports</p>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-slate-700 hover:text-white transition">
                        <i class="fas fa-chart-line w-5"></i>
                        <span>Stock Report</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-slate-700 hover:text-white transition">
                        <i class="fas fa-chart-bar w-5"></i>
                        <span>Movement Report</span>
                    </a>
                </div>
                @endif
            @endauth
            
            <!-- User Management - HANYA Super Admin -->
            @auth
                @if(auth()->user()->hasRole('super_admin'))
                <div class="pt-4 mt-2 border-t border-slate-700">
                    <p class="text-xs text-gray-500 uppercase tracking-wider px-3 mb-2">Administration</p>
                    <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-slate-700 hover:text-white transition {{ request()->routeIs('users.*') ? 'bg-indigo-600 text-white' : '' }}">
                        <i class="fas fa-users w-5"></i>
                        <span>User Management</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-slate-700 hover:text-white transition">
                        <i class="fas fa-history w-5"></i>
                        <span>Audit Logs</span>
                    </a>
                </div>
                @endif
            @endauth
            
            <!-- Auditor Menu - HANYA Auditor -->
            @auth
                @if(auth()->user()->hasRole('auditor'))
                <div class="pt-4 mt-2 border-t border-slate-700">
                    <p class="text-xs text-gray-500 uppercase tracking-wider px-3 mb-2">Audit</p>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-slate-700 hover:text-white transition">
                        <i class="fas fa-file-alt w-5"></i>
                        <span>Audit Reports</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-slate-700 hover:text-white transition">
                        <i class="fas fa-history w-5"></i>
                        <span>Activity Logs</span>
                    </a>
                </div>
                @endif
            @endauth
            
        </div>
    </nav>
    
    <!-- Footer Sidebar -->
    <div class="p-4 border-t border-slate-700">
        <div class="text-xs text-gray-500 text-center">
            <i class="fas fa-shield-alt mr-1"></i> MangoWMS v2.0
        </div>
    </div>
</aside>

<!-- Overlay untuk mobile -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden"></div>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    });
    
    overlay?.addEventListener('click', function() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    });
</script>
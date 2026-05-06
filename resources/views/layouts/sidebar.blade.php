<aside id="sidebar" class="w-64 bg-slate-800 border-r border-slate-700 flex flex-col transition-sidebar -translate-x-full lg:translate-x-0 fixed lg:relative inset-y-0 left-0 z-30">
    <!-- Logo -->
    <div class="p-4 border-b border-slate-700">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
            <div class="w-9 h-9 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg transition-all duration-300 group-hover:scale-105">
                <span class="text-white font-bold text-xl">M</span>
            </div>
            <span class="font-bold text-xl bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">MangoWMS</span>
        </a>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="flex-1 py-4 overflow-y-auto sidebar-scroll">
        <div class="px-3 space-y-1">
            
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white shadow-lg' : 'hover:bg-slate-700/50' }}">
                <i class="fas fa-tachometer-alt w-5"></i>
                <span>Dashboard</span>
                @if(request()->routeIs('dashboard'))
                <i class="fas fa-circle text-indigo-300 text-xs ml-auto"></i>
                @endif
            </a>
            
            <!-- Products -->
            <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('products.*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white shadow-lg' : 'hover:bg-slate-700/50' }}">
                <i class="fas fa-box w-5"></i>
                <span>Products</span>
                @if(request()->routeIs('products.*'))
                <i class="fas fa-circle text-indigo-300 text-xs ml-auto"></i>
                @endif
            </a>
            
            <!-- Batches -->
            <a href="{{ route('batches.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('batches.*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white shadow-lg' : 'hover:bg-slate-700/50' }}">
                <i class="fas fa-layer-group w-5"></i>
                <span>Batches</span>
                @if(request()->routeIs('batches.*'))
                <i class="fas fa-circle text-indigo-300 text-xs ml-auto"></i>
                @endif
            </a>

            <!-- Suppliers -->
            <a href="{{ route('suppliers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('suppliers.*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white shadow-lg' : 'hover:bg-slate-700/50' }}">
                <i class="fas fa-truck w-5"></i>
                <span>Suppliers</span>
                @if(request()->routeIs('suppliers.*'))
                <i class="fas fa-circle text-indigo-300 text-xs ml-auto"></i>
                @endif
            </a>

            <!-- Stock In -->
            <a href="{{ route('stock-in.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('stock-in.*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white shadow-lg' : 'hover:bg-slate-700/50' }}">
                <i class="fas fa-arrow-down w-5 text-emerald-400"></i>
                <span>Stock In</span>
                @if(request()->routeIs('stock-in.*'))
                <i class="fas fa-circle text-emerald-300 text-xs ml-auto"></i>
                @endif
            </a>

            <!-- Customers -->
            <a href="{{ route('customers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('customers.*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white shadow-lg' : 'hover:bg-slate-700/50' }}">
                <i class="fas fa-users w-5"></i>
                <span>Customers</span>
                @if(request()->routeIs('customers.*'))
                <i class="fas fa-circle text-indigo-300 text-xs ml-auto"></i>
                @endif
            </a>

            <!-- Stock Out -->
            <a href="{{ route('stock-out.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('stock-out.*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white shadow-lg' : 'hover:bg-slate-700/50' }}">
                <i class="fas fa-arrow-up w-5 text-red-400"></i>
                <span>Stock Out</span>
                @if(request()->routeIs('stock-out.*'))
                <i class="fas fa-circle text-red-300 text-xs ml-auto"></i>
                @endif
            </a>

            <!-- Stock Movement History -->
            <a href="{{ route('stock-movement.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('stock-movement.*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white shadow-lg' : 'hover:bg-slate-700/50' }}">
                <i class="fas fa-history w-5 text-blue-400"></i>
                <span>Stock Movement</span>
                @if(request()->routeIs('stock-movement.*'))
                <i class="fas fa-circle text-blue-300 text-xs ml-auto"></i>
                @endif
            </a>
            
            <!-- Reports -->
            @auth
                @if(auth()->user()->hasAnyRole(['super_admin', 'director', 'warehouse_manager']))
                <div class="pt-4 mt-2 border-t border-slate-700">
                    <p class="text-xs text-gray-500 uppercase tracking-wider px-3 mb-2">Reports</p>
                    <a href="{{ route('stock-report.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('stock-report.*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white shadow-lg' : 'hover:bg-slate-700/50' }}">
                        <i class="fas fa-chart-line w-5"></i>
                        <span>Stock Report</span>
                    </a>
                    <a href="{{ route('movement-report.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('movement-report.*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white shadow-lg' : 'hover:bg-slate-700/50' }}">
                        <i class="fas fa-chart-bar w-5"></i>
                        <span>Movement Report</span>
                    </a>
                </div>
                @endif
            @endauth
            
            <!-- User Management & Audit Logs -->
            @auth
                @if(auth()->user()->hasRole('super_admin'))
                <div class="pt-4 mt-2 border-t border-slate-700">
                    <p class="text-xs text-gray-500 uppercase tracking-wider px-3 mb-2">Administration</p>
                    <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white shadow-lg' : 'hover:bg-slate-700/50' }}">
                        <i class="fas fa-users w-5"></i>
                        <span>User Management</span>
                    </a>
                    <a href="{{ route('activity-logs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('activity-logs.*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white shadow-lg' : 'hover:bg-slate-700/50' }}">
                        <i class="fas fa-history w-5"></i>
                        <span>Audit Logs</span>
                    </a>
                </div>
                @endif
            @endauth
            
            <!-- Auditor Menu -->
            @auth
                @if(auth()->user()->hasRole('auditor'))
                <div class="pt-4 mt-2 border-t border-slate-700">
                    <p class="text-xs text-gray-500 uppercase tracking-wider px-3 mb-2">Audit</p>
                    <a href="{{ route('activity-logs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:text-white transition-all duration-200 {{ request()->routeIs('activity-logs.*') ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white shadow-lg' : 'hover:bg-slate-700/50' }}">
                        <i class="fas fa-history w-5"></i>
                        <span>Audit Logs</span>
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
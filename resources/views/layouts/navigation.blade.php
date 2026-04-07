<nav class="bg-gray-800 border-b border-gray-700 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-lg">M</span>
                    </div>
                    <span class="font-bold text-xl text-white hidden sm:block">MangoWMS</span>
                </a>
                
                <div class="hidden md:flex ml-10 space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-indigo-400' : '' }}">
                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                    </a>
                    <a href="{{ route('products.index') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('products.*') ? 'text-indigo-400' : '' }}">
                        <i class="fas fa-box mr-2"></i> Products
                    </a>
                    <a href="{{ route('batches.index') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('batches.*') ? 'text-indigo-400' : '' }}">
                        <i class="fas fa-layer-group mr-2"></i> Batches
                    </a>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="hidden md:flex items-center gap-2 text-sm text-gray-300">
                    <div class="w-7 h-7 bg-indigo-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-xs">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <span>{{ Auth::user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-400 hover:text-red-300 text-sm transition">
                        <i class="fas fa-sign-out-alt mr-1"></i> <span class="hidden sm:inline">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
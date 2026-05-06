<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MangoWMS - Login | Enterprise WMS</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">
    
    <div class="w-full max-w-md px-6">
        
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-xl">
                    <span class="text-white font-bold text-3xl">M</span>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-white">MangoWMS</h1>
            <p class="text-gray-400 mt-2">Enterprise Warehouse Management System</p>
        </div>
        
        <!-- Login Card -->
        <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700 p-8 shadow-2xl">
            <h2 class="text-2xl font-semibold text-white mb-6">Sign In</h2>
            
            @if(session('status'))
                <div class="bg-emerald-900/50 border border-emerald-700 text-emerald-300 px-4 py-3 rounded-lg mb-4">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Email -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-envelope mr-2"></i> Email Address
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:outline-none transition">
                    @error('email')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-lock mr-2"></i> Password
                    </label>
                    <input type="password" name="password" required
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:outline-none transition">
                    @error('password')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Remember Me -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 border-slate-700 rounded focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-400">Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-indigo-400 hover:text-indigo-300 transition">
                            Forgot password?
                        </a>
                    @endif
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg transition duration-200">
                    <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                </button>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-gray-500 text-xs">
                <i class="fas fa-shield-alt mr-1"></i> MangoWMS Enterprise v2.0
            </p>
        </div>
        
    </div>
    
</body>
</html>
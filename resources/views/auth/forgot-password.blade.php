<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MangoWMS') }} - Forgot Password</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); min-height: 100vh; }
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
            <p class="text-gray-400 mt-2">Reset your password</p>
        </div>
        
        <!-- Forgot Password Card -->
        <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700 p-8 shadow-2xl">
            <h2 class="text-2xl font-semibold text-white mb-4">Forgot Password?</h2>
            <p class="text-gray-400 text-sm mb-6">
                Enter your email address and we'll send you a link to reset your password.
            </p>
            
            @if(session('status'))
                <div class="bg-emerald-900/50 border border-emerald-700 text-emerald-300 px-4 py-3 rounded-lg mb-4">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-envelope mr-2"></i> Email Address
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-indigo-500 focus:outline-none">
                    @error('email')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg transition">
                    <i class="fas fa-paper-plane mr-2"></i> Send Reset Link
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 text-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Sign In
                </a>
            </div>
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
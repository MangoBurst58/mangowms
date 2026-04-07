<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BatchController;
use Illuminate\Support\Facades\Route;

// ============================================
// PUBLIC ROUTES
// ============================================
Route::get('/', function () {
    return view('welcome');
});

// ============================================
// DASHBOARD ROUTE
// ============================================
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ============================================
// AUTHENTICATED ROUTES
// ============================================
Route::middleware('auth')->group(function () {
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('products', ProductController::class);
    Route::resource('batches', BatchController::class);
});

// ============================================
// USER MANAGEMENT - ONLY SUPER ADMIN
// ============================================
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->group(function () {
    Route::resource('users', App\Http\Controllers\UserController::class);
});

// ============================================
// TEST ROUTE
// ============================================
Route::get('/admin-test', function () {
    $user = auth()->user();
    $roles = $user->getRoleNames()->implode(', ');
    
    return response()->json([
        'message' => 'You are authorized!',
        'user' => $user->name,
        'email' => $user->email,
        'roles' => $roles
    ]);
})->middleware(['auth', 'role:super_admin']);

require __DIR__.'/auth.php';
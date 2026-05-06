<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\StockReportController;
use App\Http\Controllers\MovementReportController;
use App\Http\Controllers\ActivityLogController;
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
    
    // Suppliers Management
    Route::resource('suppliers', SupplierController::class);
    
    // Stock In Management
    Route::get('/stock-in', [StockInController::class, 'index'])->name('stock-in.index');
    Route::get('/stock-in/create', [StockInController::class, 'create'])->name('stock-in.create');
    Route::post('/stock-in', [StockInController::class, 'store'])->name('stock-in.store');
    Route::get('/stock-in/{goodsReceipt}', [StockInController::class, 'show'])->name('stock-in.show');
});

// ============================================
// USER MANAGEMENT - ONLY SUPER ADMIN
// ============================================
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->group(function () {
    Route::resource('users', App\Http\Controllers\UserController::class);
});

// Customers
Route::resource('customers', CustomerController::class);

// Stock Out
Route::get('/stock-out', [StockOutController::class, 'index'])->name('stock-out.index');
Route::get('/stock-out/create', [StockOutController::class, 'create'])->name('stock-out.create');
Route::post('/stock-out', [StockOutController::class, 'store'])->name('stock-out.store');
Route::get('/stock-out/{salesOrder}', [StockOutController::class, 'show'])->name('stock-out.show');

// Stock Movement History
Route::get('/stock-movement', [StockMovementController::class, 'index'])->name('stock-movement.index');

// Stock Report
Route::get('/stock-report', [StockReportController::class, 'index'])->name('stock-report.index');
Route::get('/stock-report/export', [StockReportController::class, 'export'])->name('stock-report.export');

// Movement Report
Route::get('/movement-report', [MovementReportController::class, 'index'])->name('movement-report.index');
Route::get('/movement-report/export', [MovementReportController::class, 'export'])->name('movement-report.export');

// ============================================
// ACTIVITY LOGS - SUPER ADMIN & AUDITOR ONLY
// ============================================
Route::middleware(['auth', 'role:super_admin,auditor'])->group(function () {
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
});

//============================================
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
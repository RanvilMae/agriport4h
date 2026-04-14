<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\AnnouncementController;

use Illuminate\Support\Facades\Route;

// 1. Public Routes
Route::get('/', function () {
    return view('auth/login');
});

// 2. Protected Routes (Must be Logged In)
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard - Only one definition needed
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('announcements', AnnouncementController::class);

    // Members Management
    Route::resource('members', MemberController::class);
    Route::get('/members/verify-action', [MemberController::class, 'verify'])->name('members.verify');
    Route::get('/members/{member}/id', [MemberController::class, 'showId'])->name('members.id');
    Route::get('/members/{member}/download-id', [MemberController::class, 'downloadIdCard'])->name('members.download-id');

    // ORG Management
    Route::resource('organizations', OrganizationController::class);

    // --- ADMIN ONLY SECTION ---
    Route::group([
        'middleware' => [
            'auth',
            function ($request, $next) {
                if (auth()->user()->role !== 'Admin') {
                    abort(403, 'Access Denied: Admins Only.');
                }
                return $next($request);
            }
        ]
    ], function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/accept', [UserController::class, 'accept'])->name('users.accept');
    });

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
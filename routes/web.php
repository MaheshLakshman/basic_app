<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\OrganizationController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('organizations', OrganizationController::class);
    Route::resource('languages', \App\Http\Controllers\LanguageController::class);
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::resource('roles', \App\Http\Controllers\RoleController::class);
    Route::resource('permissions', \App\Http\Controllers\PermissionController::class);
    Route::resource('branches', \App\Http\Controllers\BranchController::class);
    Route::resource('attendances', \App\Http\Controllers\AttendanceController::class);
    Route::resource('services', \App\Http\Controllers\ServiceController::class);
    Route::resource('plans', \App\Http\Controllers\PlanController::class);
    Route::resource('members', \App\Http\Controllers\MemberController::class);
    Route::resource('trainers', \App\Http\Controllers\TrainerController::class);
    Route::resource('subscriptions', \App\Http\Controllers\SubscriptionController::class);

    // Price Management
    Route::resource('member_price_assignments', \App\Http\Controllers\Admin\MemberPriceAssignmentController::class)->names('admin.member_price_assignments');
    Route::resource('account_locks', \App\Http\Controllers\Admin\AccountLockController::class)->names('admin.account_locks');
    Route::resource('price_adjustments', \App\Http\Controllers\Admin\PriceAdjustmentController::class)->names('admin.price_adjustments');
    Route::resource('invoices', \App\Http\Controllers\Admin\InvoiceController::class)->names('admin.invoices');

    // Profile Routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

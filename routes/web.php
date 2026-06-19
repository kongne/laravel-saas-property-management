<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeaseController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\PublicPropertyController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/pricing', 'home.pricing')->name('pricing');
Route::view('/features', 'home.features')->name('features');
Route::view('/contact', 'home.contact')->name('contact');
Route::post('/contact', [HomeController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/lang/{locale}', [App\Http\Controllers\LocaleController::class, 'switch'])->name('locale.switch');

Route::get('/listings', [PublicPropertyController::class, 'index'])->name('listings.index');
Route::get('/listings/{property}', [PublicPropertyController::class, 'show'])->name('listings.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:3,10');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::get('/auth/{provider}', [SocialLoginController::class, 'redirect'])->name('social.redirect')->middleware('throttle:10,5');
Route::get('/auth/{provider}/callback', [SocialLoginController::class, 'callback'])->name('social.callback')->middleware('throttle:10,5');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/two-factor/challenge', [TwoFactorController::class, 'challenge'])->name('two-factor.challenge');
    Route::post('/two-factor/verify', [TwoFactorController::class, 'verify'])->name('two-factor.verify')->middleware('throttle:5,5');
    Route::get('/two-factor/recovery', [TwoFactorController::class, 'recovery'])->name('two-factor.recovery');
    Route::post('/two-factor/recovery/verify', [TwoFactorController::class, 'verifyRecovery'])->name('two-factor.recovery.verify')->middleware('throttle:3,30');

    Route::middleware('two-factor')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/search', [SearchController::class, 'search'])->name('search.global');

        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

        Route::post('/two-factor/enable', [TwoFactorController::class, 'enable'])->name('two-factor.enable');
        Route::post('/two-factor/disable', [TwoFactorController::class, 'disable'])->name('two-factor.disable');
        Route::post('/two-factor/send-code', [ProfileController::class, 'sendTwoFactorCode'])->name('two-factor.send-code')->middleware('throttle:3,5');

        Route::get('/profile/security', [ProfileController::class, 'security'])->name('profile.security');
        Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/profile/unlink-social/{provider}', [ProfileController::class, 'unlinkSocial'])->name('profile.unlink-social');
        Route::post('/profile/email', [ProfileController::class, 'updateEmail'])->name('profile.email');
        Route::get('/profile/email/confirm', [ProfileController::class, 'confirmEmail'])->name('profile.confirm-email');

        Route::middleware('role:admin,landlord')->group(function () {
            Route::resource('properties', PropertyController::class);
            Route::resource('units', UnitController::class);
            Route::resource('tenants', TenantController::class);

            Route::resource('leases', LeaseController::class);
            Route::post('/leases/{lease}/terminate', [LeaseController::class, 'terminate'])->name('leases.terminate');
            Route::post('/leases/{lease}/renew', [LeaseController::class, 'renew'])->name('leases.renew');

            Route::post('/payments/{payment}/mark-as-paid', [PaymentController::class, 'markAsPaid'])->name('payments.mark-as-paid');
            Route::get('/payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');

            Route::post('/maintenance/{maintenanceRequest}/resolve', [MaintenanceController::class, 'resolve'])->name('maintenance.resolve');
            Route::post('/maintenance/{maintenanceRequest}/assign', [MaintenanceController::class, 'assign'])->name('maintenance.assign');

            Route::get('/export/payments', [ExportController::class, 'payments'])->name('payments.export');
            Route::get('/export/leases', [ExportController::class, 'leases'])->name('leases.export');
            Route::get('/export/tenants', [ExportController::class, 'tenants'])->name('tenants.export');
            Route::get('/export/properties', [ExportController::class, 'properties'])->name('properties.export');
            Route::get('/export/maintenance', [ExportController::class, 'maintenance'])->name('maintenance.export');
            Route::get('/payments/{payment}/receipt-pdf', [ExportController::class, 'paymentPdf'])->name('payments.receipt-pdf');

            Route::get('/audit', [AuditLogController::class, 'index'])->name('audit.index');

            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
            Route::post('/users/bulk-deactivate', [UserController::class, 'bulkDeactivate'])->name('users.bulk-deactivate');
            Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::post('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        });

        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create')->middleware('role:admin,landlord');
        Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store')->middleware('role:admin,landlord');
        Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
        Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit')->middleware('role:admin,landlord');
        Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update')->middleware('role:admin,landlord');
        Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy')->middleware('role:admin,landlord');

        Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
        Route::get('/maintenance/create', [MaintenanceController::class, 'create'])->name('maintenance.create');
        Route::post('/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');
        Route::get('/maintenance/{maintenanceRequest}', [MaintenanceController::class, 'show'])->name('maintenance.show');
        Route::get('/maintenance/{maintenanceRequest}/edit', [MaintenanceController::class, 'edit'])->name('maintenance.edit')->middleware('role:admin,landlord');
        Route::put('/maintenance/{maintenanceRequest}', [MaintenanceController::class, 'update'])->name('maintenance.update')->middleware('role:admin,landlord');
        Route::delete('/maintenance/{maintenanceRequest}', [MaintenanceController::class, 'destroy'])->name('maintenance.destroy')->middleware('role:admin,landlord');
    });
});

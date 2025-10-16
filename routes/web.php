<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\InvitationController;
use App\Http\Controllers\Admin\ShortUrlController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// Route::get('storage-link', function () {
//     Artisan::call('storage:link');
// });

Route::middleware(['guest'])->group(function () {
    Route::view('login', 'admin.auth.login');
    Route::view('/', 'admin.auth.login')->name('admin');
    Route::post('login', [AdminController::class, 'login'])->name('admin.login');
    Route::get('invitation-accept/{token}', [AdminController::class, 'acceptInvitation'])->name('invitations.accept');
});

Route::group(['middleware' => 'auth',  'as' => 'admin.'], function () {

    Route::get('dashboard/', [AdminController::class, 'dashboard'])->name('dashboard');

    // ===Profile====#
    Route::get('profile-setting/', [AdminController::class, 'profileSetting'])->name('profile.setting');
    Route::post('/change-password', [AdminController::class, 'changePassword'])->name('change.password');
    Route::post('/profile/update-images', [AdminController::class, 'updateImages'])->name('profile.update.images');
    Route::get('logout/', [AdminController::class, 'logout'])->name('logout');

    // ===Mail Setting====#
    Route::get('mail-setting/', [AdminController::class, 'mailSetting'])->name('mail.setting');
    Route::post('/update-mail', [AdminController::class, 'updateMail'])->name('update.mail');

    Route::get('/get-short-urls', [ShortUrlController::class, 'getShortUrls']);
    Route::post('/short-urls', [ShortUrlController::class, 'store'])->name('shorturls.store');
    Route::get('/download-urls', [ShortUrlController::class, 'downloadUrls']);

    Route::middleware('role:SuperAdmin')->group(function () {
        Route::get('/get-companies', [CompanyController::class, 'getCompanies']);
        Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    });

    Route::middleware('role:Admin')->group(function () {
        Route::get('/get-users', [AdminController::class, 'getUsers']);
        Route::post('/invitations', [InvitationController::class, 'store'])->name('invitations.store');
    });

});

Route::get('re/{short_code?}', [ShortUrlController::class, 'redirectToLongUrl'])->name('shorturl.redirect');

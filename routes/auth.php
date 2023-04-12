<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\AuthenticatedByPhoneSessionController;
use App\Http\Controllers\Auth\AuthenticatedByEmailSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware', ['web']], function(){

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('useravailablity', [RegisteredUserController::class, 'userAvailable'])->name('check-user-availablity');

    Route::post('change-pass-phone', [RegisteredUserController::class, 'sendPhoneOtpForPassChange'])->name('change-pass-by-phone');

    Route::get('input-pass-change-token/{phone}', [RegisteredUserController::class, 'passChangeByPhoneAlert'])->name('input-pass-change-token-phone');

    Route::post('confirm-pass-change', [RegisteredUserController::class, 'confirmPassChange'])->name('confirm-pass-change');
    Route::get('pass-change/{phone}/{token}', [RegisteredUserController::class, 'createPassChangeForm'])->name('pass-change-form');

    Route::post('change-pass', [RegisteredUserController::class, 'changePass'])->name('change-pass');
    //Route::post('home', [RegisteredUserController::class, 'toHome'])->name('to-home');
    //Route::get('home', [RegisteredUserController::class, 'toHomeView'])->name('to-home-view');

    

                

    //Route::post('register', [RegisteredUserController::class, 'store']);

    Route::post('registerP', [RegisteredUserController::class, 'storeByPhone'])->name('register-by-phone');
    Route::post('registerE', [RegisteredUserController::class, 'storeByEmail'])->name('register-by-email');

    Route::get('login/{back?}/{id?}', [AuthenticatedSessionController::class, 'create'])->name('login');

    Route::post('loginPS1', [AuthenticatedByPhoneSessionController::class, 'passPage'])
    ->name('enter-staticly-by-phone');

    Route::post('loginPD1', [AuthenticatedByPhoneSessionController::class, 'dynamicPassPage'])
    ->name('enter-dynamicly-by-phone');


    Route::post('loginES1', [AuthenticatedByEmailSessionController::class, 'passPage'])
    ->name('enter-staticly-by-email');

    Route::post('loginPS2', [AuthenticatedByPhoneSessionController::class, 'storeStatic'])
    ->name('staticlogin-by-phone');

    Route::post('loginPD2', [AuthenticatedByPhoneSessionController::class, 'storeDynamic'])
    ->name('dynamiclogin-by-phone');


    Route::post('loginES2', [AuthenticatedByEmailSessionController::class, 'storeStatic'])
    ->name('staticlogin-by-email');










    


    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('resend-otp', [RegisteredUserController::class, 'sendOTP'])
                ->name('resend-otp');

    Route::get('phone-confirm', [RegisteredUserController::class, 'phoneConfirm'])->name('phone-confirm');

    Route::post('confirm-otp', [RegisteredUserController::class, 'confirmOTP'])
                ->name('confirm-otp');

    Route::get('otp-confirmation-page', [RegisteredUserController::class, 'confirmPage'])
                ->name('confirmation.page');







    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
 
});


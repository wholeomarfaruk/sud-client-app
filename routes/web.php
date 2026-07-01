<?php

use App\Livewire\Client\Auth\ForgotPassword;
use App\Livewire\Client\Auth\Login;
use App\Livewire\Client\Auth\Otp;
use App\Livewire\Client\Dashboard\Dashboard;
use App\Livewire\Client\Installments\Installments;
use App\Livewire\Client\News\News;
use App\Livewire\Client\Noticeboard\Noticeboard;
use App\Livewire\Client\Notifications\Notifications;
use App\Livewire\Client\Offers\Offers;
use App\Livewire\Client\Payments\PaymentHistory;
use App\Livewire\Client\Profile\ChangePassword;
use App\Livewire\Client\Profile\Profile;
use App\Livewire\Client\Properties\MyProperties;
use App\Livewire\Client\Properties\PropertyDetail;
use App\Livewire\Client\Welcome\Welcome;
use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\Website\Home\Home::class)->name('home');

// Customer Portal — client.starunitydevelopment.com (UI phase: mock data, no auth yet)
Route::name('client.')->group(function () {
    Route::get('/welcome', Welcome::class)->name('welcome');
    Route::get('/login', Login::class)->name('login');
    Route::get('/forgot-password', ForgotPassword::class)->name('forgot-password');
    Route::get('/otp-verification', Otp::class)->name('otp');

    Route::get('/my-properties', MyProperties::class)->name('my-properties');
    Route::get('/my-properties/{property}', PropertyDetail::class)->name('property-detail');
    Route::get('/payment-history', PaymentHistory::class)->name('payment-history');
    Route::get('/installments', Installments::class)->name('installments');
    Route::get('/noticeboard', Noticeboard::class)->name('noticeboard');
    Route::get('/offers', Offers::class)->name('offers');
    Route::get('/news', News::class)->name('news');
    Route::get('/notifications', Notifications::class)->name('notifications');
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/change-password', ChangePassword::class)->name('change-password');
});

Route::get('/dashboard', Dashboard::class)->name('dashboard');

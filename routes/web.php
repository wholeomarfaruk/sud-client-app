<?php

use App\Livewire\Client\Auth\ForgotPassword;
use App\Livewire\Client\Auth\Login;
use App\Livewire\Client\Auth\Otp;
use App\Livewire\Client\Auth\ResetPassword;
use App\Livewire\Client\Dashboard\Dashboard;
use App\Livewire\Client\Invoices\InvoiceDetail;
use App\Livewire\Client\Invoices\Invoices;
use App\Livewire\Client\News\News;
use App\Livewire\Client\Notifications\Notifications;
use App\Livewire\Client\Offers\Offers;
use App\Livewire\Client\Payments\PaymentHistory;
use App\Livewire\Client\Profile\ChangePassword;
use App\Livewire\Client\Profile\Profile;
use App\Livewire\Client\Properties\MyProperties;
use App\Livewire\Client\Properties\PropertyDetail;
use App\Livewire\Client\Welcome\Welcome;
use App\Services\Erp\ErpApiException;
use App\Services\Erp\ErpClient;
use Illuminate\Support\Facades\Route;

Route::get('/', Welcome::class)->name('home');

// Customer Portal — client.starunitydevelopment.com (data via the ERP API; see App\Services\Erp\ErpClient)
Route::name('client.')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/forgot-password', ForgotPassword::class)->name('forgot-password');
    Route::get('/reset-password', ResetPassword::class)->name('reset-password');
    Route::get('/otp-verification', Otp::class)->name('otp');
    Route::post('/logout', function (ErpClient $erp) {
        if ($token = session('client.access_token')) {
            try {
                $erp->logout($token);
            } catch (ErpApiException $e) {
                // Token may already be expired/invalid on the ERP side — still log out locally.
            }
        }

        session()->forget(['client.access_token', 'client.user']);

        return redirect()->route('client.login');
    })->name('logout');

    Route::get('/my-properties', MyProperties::class)->middleware('client.auth')->name('my-properties');
    Route::get('/my-properties/{sale}/{saleUnit}', PropertyDetail::class)->middleware('client.auth')->name('property-detail');
    Route::get('/invoices', Invoices::class)->middleware('client.auth')->name('invoices');
    Route::get('/invoices/{sale}', InvoiceDetail::class)->middleware('client.auth')->name('invoice-detail');
    Route::get('/payment-history', PaymentHistory::class)->middleware('client.auth')->name('payment-history');
    Route::get('/offers', Offers::class)->name('offers');
    Route::get('/news', News::class)->name('news');
    Route::get('/notifications', Notifications::class)->name('notifications');
    Route::get('/profile', Profile::class)->middleware('client.auth')->name('profile');
    Route::get('/change-password', ChangePassword::class)->name('change-password');
});

Route::get('/dashboard', Dashboard::class)->middleware('client.auth')->name('dashboard');

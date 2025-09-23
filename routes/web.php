<?php

use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\TransactionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProductController as ControllersProductController;
use App\Http\Controllers\ReportController;
use App\Http\Middleware\HasCart;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('admin', fn() => to_route('login'));

Route::middleware(['auth.customer'])
    ->group(function () {
        Route::get('product', [ProductController::class, 'index'])->name('product.index');
        Route::get('cart', [CartController::class, 'index'])
            ->name('cart.index');
        Route::get('checkout', [CheckoutController::class, 'index'])
            ->middleware(HasCart::class)
            ->name('checkout.index');
        Route::get('transaction', [TransactionController::class, 'index'])
            ->name('transaction.index');
        Route::get('transaction/{transaction}', [TransactionController::class, 'invoice'])
            ->name('transaction.invoice');
        Route::get('payment/{transaction}', [PaymentController::class, 'index'])
            ->name('payment.index');
        Route::put('payment/{transaction}/confirm', [PaymentController::class, 'confirm'])
            ->name('payment.confirm');
    });

Route::prefix('admin')->group(function () {
    Route::middleware('auth')->group(function () {

        Route::view('dashboard', 'dashboard')
            ->name('dashboard');

        Route::view('profile', 'profile')
            ->name('profile');

        Route::as('admin.')->group(function () {
            Route::get('transaction/{transaction}', [TransactionController::class, 'invoice'])
                ->name('transaction.invoice');

            Route::get('category', [CategoryController::class, 'index'])->name('category.index');
            Route::controller(ControllersProductController::class)
                ->prefix('product')
                ->as('product.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('show/{product}', 'show')->name('show');
                });

            Route::get('transaction', [AdminTransactionController::class, 'index'])->name('transaction.index');
            Route::get('customer', [CustomerController::class, 'index'])->name('customer.index');
            Route::get('employee', [EmployeeController::class, 'index'])->name('employee.index');
            Route::get('report', [ReportController::class, 'index'])->name('report.index');
            Route::get('report/print', [ReportController::class, 'print'])->name('report.print');
        });
    });

    require __DIR__ . '/auth.php';
});

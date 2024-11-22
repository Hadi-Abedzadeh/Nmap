<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\CVEController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Project\ContactProjectController;
use App\Http\Controllers\Project\CVEProjectController;
use App\Http\Controllers\Project\SubscriptionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\DashboardController;

\Illuminate\Support\Facades\Auth::loginUsingId(2);

Route::get('/', [HomeController::class, 'index'])->name('/');
Route::post('/consultations', [ConsultationController::class, 'store'])
    ->name('consultations.store');
Route::post('payments/callback', [PaymentController::class, 'callback'])
    ->name('payments.callback');


Route::middleware(['auth', 'verified'])->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    //Route::get('/financial', [DashboardController::class, 'financial'])->name('financial');
    Route::get('/cve-list/{cveId}', [DashboardController::class, 'single'])->name('single');
    Route::post('/subscribe', [DashboardController::class, 'subscribe'])/*->where('type', 'vendor|product')*/->name('subscribe');

    Route::any('/payment', [\App\Http\Controllers\PaymentController::class, 'initiate'])->name('payment.initiate');
    Route::post('/payment/callback', [\App\Http\Controllers\PaymentController::class, 'verify'])->name('payment.verify');

//    Route::get('payment', [\App\Http\Controllers\PaymentController::class, 'initiatePayment']);
//    Route::any('payment/callback', [\App\Http\Controllers\PaymentController::class, 'callback']);


    /*Route::get('/items', [ItemController::class, 'index'])->name('items');
    Route::get('/items/filter', [ItemController::class, 'itemFilter'])->name('itemFilter');
    Route::post('/items/add-items', [ItemController::class, 'addItems'])->name('addItems');*/

    // Tickets
    Route::resource('/tickets', TicketController::class)
        ->except('edit', 'destroy');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profiles.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profiles.update');

    // Plans
    Route::get('plans', [PlanController::class, 'show'])
        ->name('plans.show');

    // Orders
    Route::resource('orders', OrderController::class)
        ->only('index', 'store', 'show');

    // Payments
    Route::resource('payments', PaymentController::class)
        ->only('store');

    // cve
    Route::resource('/cve', CVEController::class)
        ->only(['index', 'show']);

    // Items
    Route::get('/items', [ItemController::class, 'index'])
        ->name('items.index');

    // Subscriptions
    Route::resource('/subscriptions', SubscriptionController::class)
        ->only(['create', 'store']);

    // Contacts
    Route::resource('/contacts', ContactController::class)
        ->only(['index', 'store', 'destroy']);

    // Modules
    Route::resource('/modules/nmap', \App\Http\Controllers\Modules\NmapController::class);

    // Projects

    Route::resource('/projects', ProjectController::class)->except(['show', 'edit', 'update']);
    Route::get('/projects/{project}/{section?}', [ProjectController::class, 'show'])->name('projects.show');

    // {Section} Project
    Route::get('/projects/{project}/cve/{cve}', [CVEProjectController::class, 'show'])
        ->name('project.cves.show');
    Route::patch('/project-cve', [CVEProjectController::class, 'update'])
        ->name('project.cves.update');
    Route::post('/project-cve-notification', [CVEProjectController::class, 'notification'])
        ->name('project.cves.notification');
    Route::resource('/project-contacts', ContactProjectController::class)
        ->only('store', 'destroy');
    Route::delete('/project-subscriptions/{project_subscription}', [SubscriptionController::class, 'destroy'])
        ->name('project-subscriptions.destroy');
});


Auth::routes();

require __DIR__.'/auth.php';


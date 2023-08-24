<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::namespace('Main')->middleware('auth')->group(function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::controller(DashboardController::class)->as('dashboard.')->prefix('dashboard')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/chart-in-out-medicines', 'chartInOutMedicines')->name('chart.inout.medicines');
        Route::post('/chart-in-out-by-category', 'pieChartInOutByCategory')->name('chart.inout.category');
    });

    Route::controller(CategoryController::class)
        ->as('category.')
        ->prefix('category')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/store', 'store')->name('store');
            Route::post('/update', 'update')->name('update');
        });

    Route::controller(MedicineController::class)
        ->as('medicine.')
        ->prefix('medicine')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/store', 'store')->name('store');
            Route::post('/update', 'update')->name('update');
        });

    Route::controller(BatchController::class)
        ->as('batch.')
        ->prefix('batch')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::get('/medicine-detail/{medicine_id}', 'medicineDetail')->name('medicine.detail');
            Route::post('/store', 'store')->name('store');
            Route::post('/update', 'update')->name('update');

        });

    Route::controller(OutgoingMedicineController::class)
        ->as('outgoing.')
        ->prefix('outgoing')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::get('/detail/{id}', 'detail')->name('detail');
            Route::get('/medicine-search/{keyword}', 'medicineSearch')->name('medicine.search');
            Route::post('/store', 'store')->name('store');
            Route::post('/update', 'update')->name('update');
        });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

<?php

use App\Http\Controllers\ClientsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ReportingController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TimeEntriesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WorkTypesController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    // custom
    Route::get('clients', ClientsController::class)->name('clients.index');
    Route::get('projects', [ProjectsController::class, 'index'])->name('projects.index');
    Route::get('projects/{id}', [ProjectsController::class, 'show'])->name('projects.show');
    Route::get('projects/{id}/export', [ProjectsController::class, 'export'])->name('projects.export');
    Route::get('time-entries', TimeEntriesController::class)->name('time_entries.index');
    Route::get('work-types', WorkTypesController::class)->name('work_types.index');

    Route::get('reporting', ReportingController::class)->name('reporting.index');
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
});

Route::middleware(['admin'])->group(function () {
    Route::get('users', UsersController::class)->name('users.index');
});

Route::get('storage/exports/{filename}', function ($filename) {
    $path = storage_path('app/exports/'.$filename);

    if (file_exists($path)) {
        return response()->file($path);
    } else {
        abort(404); // File not found
    }
})->where('filename', '.+');

require __DIR__.'/auth.php';

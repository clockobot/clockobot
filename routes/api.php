<?php

use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\ReportingController;
use App\Http\Controllers\API\TimeEntryController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\WorkTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);

Route::middleware('auth:api', 'throttle:50000,1000000')->group(function () {
    // Dashboard
    Route::get('dashboard/hours', [DashboardController::class, 'get_hours_counts']);
    Route::get('dashboard/works', [DashboardController::class, 'get_latest_work']);
    Route::get('dashboard/activities', [DashboardController::class, 'get_latest_activities']);

    // Users
    Route::get('users/list', [UserController::class, 'list']);
    Route::post('details', [UserController::class, 'get_user']); // Fixme: Might follow up this one -> controller looks wrong not sure what I did.
    Route::get('users/{id}', [UserController::class, 'get_user_details_by_id']);
    Route::post('users/{id}', [UserController::class, 'update_user']);

    // Clients
    Route::get('clients/list', [ClientController::class, 'list']);
    Route::post('clients/create', [ClientController::class, 'create_client']);
    Route::get('clients/{id}/details', [ClientController::class, 'get_client']);
    Route::post('clients/{id}/update', [ClientController::class, 'update_client']);
    Route::post('clients/{id}/delete', [ClientController::class, 'delete_client']);
    Route::get('clients/{id}/projects', [ClientController::class, 'get_client_projects']); // For dropdowns, helps listing only wanted elements
    Route::post('clients/search', [ClientController::class, 'search_client']);

    // Projects
    Route::get('projects/list', [ProjectController::class, 'list']);
    Route::post('projects/create', [ProjectController::class, 'create_project']);
    Route::get('projects/{id}/details', [ProjectController::class, 'get_project']);
    Route::post('projects/{id}/update', [ProjectController::class, 'update_project']);
    Route::post('projects/{id}/delete', [ProjectController::class, 'delete_project']);
    Route::post('projects/search', [ProjectController::class, 'search_project']);

    // Work Types
    Route::get('work-types/list', [WorkTypeController::class, 'list']);
    Route::post('work-types/create', [WorkTypeController::class, 'create_work_type']);
    Route::get('work-types/{id}/details', [WorkTypeController::class, 'get_work_type']);
    Route::post('work-types/{id}/update', [WorkTypeController::class, 'update_work_type']);
    Route::post('work-types/{id}/delete', [WorkTypeController::class, 'delete_work_type']);
    Route::post('work-types/search', [WorkTypeController::class, 'search_work_type']);

    // Time entries
    Route::get('time-entries/list', [TimeEntryController::class, 'list']);
    Route::post('time-entries/init', [TimeEntryController::class, 'init_time_entry']);
    Route::post('time-entries/create', [TimeEntryController::class, 'create_time_entry']);
    Route::get('time-entries/{id}/details', [TimeEntryController::class, 'get_time_entry']);
    Route::post('time-entries/{id}/update', [TimeEntryController::class, 'update_time_entry']);
    Route::post('time-entries/{id}/delete', [TimeEntryController::class, 'delete_time_entry']);
    Route::get('time-entries/{id}/decimal-duration', [TimeEntryController::class, 'get_time_entry_decimal_duration']);
    Route::get('time-entries/{id}/hours-duration', [TimeEntryController::class, 'get_time_entry_duration_in_hours']);
    Route::get('time-entries/{user_id}/ongoing', [TimeEntryController::class, 'get_ongoing_time_entry_for_user']);

    // Reporting
    Route::get('reporting/list', [ReportingController::class, 'list']);
    Route::post('reporting/filter', [ReportingController::class, 'get_filtered_results']);
    Route::post('reporting/export', [ReportingController::class, 'get_export']);

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

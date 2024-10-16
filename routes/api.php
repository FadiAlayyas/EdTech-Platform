<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\SubmissionLogController;
use App\Http\Controllers\UserController;
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

################## User Auth Routes (['Teacher', 'Student']) ##################

Route::group([
    'prefix' => '/auth',
], function () {
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::group(['middleware' => 'auth:user'], function () {
        Route::get('/profile-details', [UserAuthController::class, 'getProfileDetails']);
        Route::post('/logout', [UserAuthController::class, 'logout']);
        Route::post('/change-password', [UserAuthController::class, 'changePassword']);
        Route::put('/profile-update', [UserAuthController::class, 'profileUpdate']);
    });
});

################## End User Auth Routes ##################

################## Role and Permission Routes ##################
Route::group([
    'prefix' => '/roles',
    'controller' => RoleController::class,
    'middleware' => ['auth:user', 'can:view-roles-permissions']
], function () {
    Route::get('/', 'getAll');
    Route::post('/', 'create')->middleware('can:create-roles-permissions');
    Route::get('/{id}/edit', 'edit')->middleware('can:update-roles-permissions');
    Route::put('/{id}/permissions', 'update')->middleware('can:update-roles-permissions');
});

Route::group([
    'prefix' => '/permissions',
    'middleware' => ['auth:user', 'can:view-roles-permissions']
], function () {
    Route::get('/user', [RoleController::class, 'getpermissions']);
});

################## User Management Routes ##################
Route::group([
    'prefix' => '/users',
    'controller' => UserController::class,
    'middleware' => ['auth:user', 'can:view-users']
], function () {
    Route::put('/{userId}/update-status', 'updateStatus')->middleware('can:update-users');
    Route::get('/', 'getAll');
    Route::get('/{id}', 'find');
    Route::post('/', 'create')->middleware('can:create-users');
    Route::put('/{id}', 'update')->middleware('can:update-users');
    Route::delete('/{id}', 'delete')->middleware('can:delete-users');
});

################## Course Management Routes ##################
Route::group([
    'prefix' => '/courses',
    'controller' => CourseController::class,
    'middleware' => ['auth:user', 'can:view-courses']
], function () {
    Route::get('/', 'getAll');
    Route::get('/{id}', 'find');
    Route::post('/', 'create')->middleware('can:create-courses');
    Route::put('/{id}', 'update')->middleware('can:update-courses');
    Route::delete('/{id}', 'delete')->middleware('can:delete-courses');
});

################## Assignment Management Routes ##################
Route::group([
    'prefix' => '/assignments',
    'controller' => AssignmentController::class,
    'middleware' => ['auth:user', 'can:view-assignments']
], function () {
    Route::get('/', 'getAll');
    Route::get('/{id}', 'find');
    Route::post('/', 'create')->middleware('can:create-assignments');
    Route::put('/{id}', 'update')->middleware('can:update-assignments');
    Route::delete('/{id}', 'delete')->middleware('can:delete-assignments');
});

################## Submission Management Routes ##################
Route::group([
    'prefix' => '/submissions',
    'controller' => SubmissionController::class,
    'middleware' => ['auth:user', 'can:view-submissions']
], function () {
    Route::post('/insert', 'insert')->middleware('can:create-submissions');
    Route::get('/', 'getAll');
    Route::get('/{id}', 'find');
    Route::post('/', 'create')->middleware('can:create-submissions');
    Route::put('/{id}', 'update')->middleware('can:update-submissions');
    Route::delete('/{id}', 'delete')->middleware('can:delete-submissions');
});

################## Submission Log Routes ##################
Route::group([
    'prefix' => '/submission-logs',
    'controller' => SubmissionLogController::class,
    'middleware' => ['auth:user', 'can:view-submissions-log']
], function () {
    Route::get('/', 'getAll');
});

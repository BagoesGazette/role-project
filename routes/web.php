<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => ['auth']], function(){
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('role/access/{id}', [RoleController::class, 'access'])->name('role.access');
    Route::post('role/send-access', [RoleController::class, 'sendAccess'])->name('role.send-access');
    Route::get('users/change-password/{id}', [UserController::class, 'changePassword'])->name('user.change-password');
    Route::post('users/new-password', [UserController::class, 'newPassword'])->name('user.new-password');

    // Resource
    Route::resource('permission', PermissionController::class);
    Route::resource('role', RoleController::class);
    Route::resource('user', UserController::class);
    
});

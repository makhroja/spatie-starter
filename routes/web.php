<?php

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

Route::get('/', function () {
    return view('rolepermision');
});

Route::get('/gate', 'Settings\GateController@gate')->name('gate.app');

Auth::routes([
    'verify' => true,
]);

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('admin')->middleware('auth')->group(function () {

    Route::resource('role', Settings\RoleController::class);

    Route::resource('permission', Settings\PermissionController::class);

    Route::resource('team', Settings\TeamController::class);

    Route::resource('user', Backend\UserController::class);

});

Route::get('/site-refresh', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('permission:cache-reset');
    Artisan::call('optimize:clear');
    Artisan::call('config:clear');
    Artisan::call('auth:clear-resets');
    return "Site refresh succesfully";
});

// 404 for undefined routes
// Route::any('/{page?}', function () {
//     return View::make('pages.error.404');
// })->where('page', '.*');

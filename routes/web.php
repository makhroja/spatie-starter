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
    return view('dashboard');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'role'], function () {
    Route::get('/', 'Settings\RoleController@index')->name('indexRole');

    Route::post('/create', 'Settings\RoleController@createRole')->name('createRole');
    Route::post('/update', 'Settings\RoleController@updateRole')->name('updateRole');

    Route::get('/show', 'Settings\RoleController@showRole')->name('showRole');
    Route::get('/edit', 'Settings\RoleController@editRole')->name('editRole');

    Route::delete('/delete', 'Settings\RoleController@deleteRole')->name('deleteRole');
});

Route::group(['prefix' => 'permission'], function () {
    Route::get('/', 'Settings\PermissionController@index')->name('indexPermission');

    Route::post('/create', 'Settings\PermissionController@createPermission')->name('createPermission');
    Route::post('/update', 'Settings\PermissionController@updatePermission')->name('updatePermission');

    Route::get('/show', 'Settings\PermissionController@showPermission')->name('showPermission');
    Route::get('/edit', 'Settings\PermissionController@editPermission')->name('editPermission');

    Route::delete('/delete', 'Settings\PermissionController@deletePermission')->name('deletePermission');
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
Route::any('/{page?}', function () {
    return View::make('pages.error.404');
})->where('page', '.*');

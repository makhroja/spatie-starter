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
    Route::group(['prefix' => 'role'], function () {
        Route::get('/', 'Settings\RoleController@index')->name('indexRole');

        Route::get('/create', 'Settings\RoleController@createRole')->name('createRole');
        Route::post('/store', 'Settings\RoleController@storeRole')->name('storeRole');
        Route::put('/update', 'Settings\RoleController@updateRole')->name('updateRole');

        Route::get('/show', 'Settings\RoleController@showRole')->name('showRole');
        Route::get('/edit/{id}', 'Settings\RoleController@editRole')->name('editRole');

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

    Route::group(['prefix' => 'team'], function () {
        Route::get('/', 'Settings\TeamController@index')->name('indexTeam');

        Route::post('/create', 'Settings\TeamController@createTeam')->name('createTeam');
        Route::post('/update', 'Settings\TeamController@updateTeam')->name('updateTeam');

        Route::get('/show', 'Settings\TeamController@showTeam')->name('showTeam');
        Route::get('/edit', 'Settings\TeamController@editTeam')->name('editTeam');

        Route::delete('/delete', 'Settings\TeamController@deleteTeam')->name('deleteTeam');
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('/', 'Backend\UserController@index')->name('indexUser');

        Route::get('/edit', 'Backend\UserController@editUser')->name('editUser');
        Route::get('/show', 'Backend\UserController@showUser')->name('showUser');

        Route::post('/create', 'Backend\UserController@createUser')->name('createUser');
        Route::post('/update', 'Backend\UserController@updateUser')->name('updateUser');

        Route::delete('/delete', 'Backend\UserController@deleteUser')->name('deleteUser');

        Route::get('/role-permision', 'Backend\UserController@showUser')->name('role-permissionUser');
    });
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

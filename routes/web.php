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

// Log files TODO: secure it - Admins only!
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');


// email address verification request
Route::get(
    'verify/{token}', 'Auth\VerifyController@verifyEmail'
)->name('verify');


// catch-all route
Route::get(
    '{path}', function () {
        return view('index');
    }
)
->where('path', '(.*)')
->name('home');

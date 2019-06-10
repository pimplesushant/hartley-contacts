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
    return redirect('/login');
});

Auth::routes(['verify' => true]);

Route::middleware('verified')->get('/home', function () {
    return redirect('/contacts');
});

Route::middleware('verified')->resource('/contacts', 'ContactController');
Route::middleware('verified')->get('/getContacts', 'ContactController@getContacts');
Route::middleware('verified')->post('/share/{id}', 'ContactController@share')->name('contacts.share');
Route::middleware('verified')->get('/export/{id}', 'ContactController@export')->name('contacts.export');
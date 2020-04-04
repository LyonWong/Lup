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

use App\Http\Controllers\Test;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ok', function() {
    return 'ok';
});

Route::get('/home', 'Home')->name('home');

Route::any('/sign/password', 'Sign\Password');
Route::any('/sign/account', 'Sign\Account');
Route::any('/sign/email', 'Sign\Email');
Route::any('/sign/telephone', 'Sign\Telephone');
Route::any('/sign/user', 'Sign\User');
Route::any('/sign-{action}', 'Sign\_');

Route::get('/test', 'Test');
Route::any('/test-sign', 'Test@sign');
Route::any('/test-auth', 'Test@auth');
Route::any('/test-user', 'Test@user');
Route::any('/test-{method}-{action}', function($name, Test $test, Request $request){
    $test->$name();
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

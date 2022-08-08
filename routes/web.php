<?php

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


Route::group(["namespace"=>"App\Http\Controllers"], function() {
    
    Route::get('/', function () { return redirect()->route('login'); });   
    Auth::routes();

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/posts', 'PostController@posts');
    Route::get('/posts/{uuid}', 'PostController@post');

    Route::get('/api/posts', 'PostController@get');
    Route::get('/api/posts/{uuid}', 'PostController@show');

    Route::middleware(['auth'])->group(function () {

        Route::prefix('api')->group(function() {
            Route::post('/posts', 'PostController@create');
            Route::put('/posts/{uuid}', 'PostController@edit');
            Route::delete('/posts/{uuid}', 'PostController@delete');
        });

    });

});

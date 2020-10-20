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

Route::get('/', function () {
    return view('home');
});

Route::post('/', 'App\Http\Controllers\RankingsController@import')->name('rankings.import');

Route::get('/download', function () {
    return response()->download(storage_path('app/public/export/exported-rankings.csv')); 
});
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
/**
 * Insert Modals
 */
Route::get('insert_modals/{type}/create', 'InsertModalController@create')->name('insert_modals.create');
Route::post('insert_modals/{type}', 'InsertModalController@store')->name('insert_modals.store');


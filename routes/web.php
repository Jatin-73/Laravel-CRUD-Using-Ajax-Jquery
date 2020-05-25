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
    return view('welcome');
});

Route::get('/student', 'StudentController@index')->name('student.form');
Route::post('/studentAdd', 'StudentController@store')->name('student.add');
Route::get('student/edit/{id}', 'StudentController@edit')->name('student.edit');
Route::put('/studentupdate', 'StudentController@update')->name('student.update');
Route::delete('/studentdelete/{id}', 'StudentController@destroy')->name('student.destroy');




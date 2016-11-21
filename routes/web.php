<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('formsite/all-forms', 'FormSiteController@getFormSiteForms');
Route::get('formsite/new-hire-results', 'FormSiteController@getNewHireResults');

Auth::routes();


Route::group(['prefix'=>'admin', 'namespace' => 'Admin', 'middleware'=>'auth'], function () {
    Route::get('/resultslog', 'AdminController@resultslog');
    Route::get('/', 'AdminController@home');
});


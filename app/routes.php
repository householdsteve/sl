<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::get('users', function()
{
    $users = User::all();

    return View::make('users')->with('users', $users);
});

Route::get('import-yoox', 'YooxController@import');
Route::get('import-armani', 'ArmaniController@import');
Route::get('export-armani/{lang}', 'ArmaniController@export');
Route::get('import-signs', 'ArmaniController@import_signs');
Route::get('update-phone', 'ArmaniController@phone');
Route::get('fix-country', 'ArmaniController@fix_country');
Route::get('geocode', 'ArmaniController@geocode');
Route::get('genderizer', 'ArmaniController@genderizer');
Route::get('postal', 'ArmaniController@create_postalcodes');
Route::get('mtm', 'ArmaniController@fix_mtm');
Route::get('type', 'ArmaniController@fix_store_type');


Route::get('merge-language/{lang}', 'YooxController@merge');
Route::get('export-language/{lang}', 'YooxController@export');
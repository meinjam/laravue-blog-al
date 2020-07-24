<?php

use Illuminate\Support\Facades\Route;

Route::get( 'app/get_tags', 'AdminController@gettags' );
Route::post( 'app/create_tag', 'AdminController@addtag' );
Route::post( 'app/edit_tag', 'AdminController@edittag' );
Route::post( 'app/delete_tag', 'AdminController@deletetag' );

Route::get( 'app/get_categories', 'AdminController@getcategories' );
Route::post( 'app/upload', 'AdminController@upload' );
Route::post( 'app/delete_image', 'AdminController@delete_upload' );
Route::post( 'app/create_category', 'AdminController@addcategory' );
Route::post( 'app/delete_category', 'AdminController@deletecategory' );

Route::get( '/', function () {
    return view( 'welcome' );
} );

Route::any( '/{slug}', function () {
    return view( 'welcome' );
} );

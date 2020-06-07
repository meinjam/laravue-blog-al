<?php

use Illuminate\Support\Facades\Route;

Route::get( 'app/get_tags', 'AdminController@gettags' );
Route::post( 'app/create_tag', 'AdminController@addtag' );
Route::post( 'app/edit_tag', 'AdminController@edittag' );
Route::post( 'app/delete_tag', 'AdminController@deletetag' );

Route::get( '/', function () {
    return view( 'welcome' );
} );

Route::any( '/{slug}', function () {
    return view( 'welcome' );
} );

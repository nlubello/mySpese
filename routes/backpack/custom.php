<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');

    CRUD::resource('expense', 'ExpenseCrudController');
    CRUD::resource('category', 'CategoryCrudController')->with(function(){
    // add extra routes to this resource
    Route::get('category/{id}/show', 'CategoryCrudController@show');
    });
    CRUD::resource('periodic', 'PeriodicCrudController');
    CRUD::resource('debit', 'DebitCrudController');
}); // this should be the absolute last line of this file

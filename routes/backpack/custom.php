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

    Route::crud('expense', 'ExpenseCrudController');
    Route::crud('category', 'CategoryCrudController');
    // add extra routes to this resource
    Route::get('category/{id}/show', 'CategoryCrudController@show');
    Route::get('category/ajax-category-options', 'CategoryCrudController@categoryOptions');

    Route::crud('periodic', 'PeriodicCrudController');
    // add extra routes to this resource
    Route::get('periodic/{id}/register', 'PeriodicCrudController@register');
    
    Route::crud('debit', 'DebitCrudController');
    Route::get('budget', 'BudgetController@dashboard');
    Route::get('charts/daily-expenses', 'Charts\DailyExpensesChartController@response')->name('charts.daily-expenses.index');
}); // this should be the absolute last line of this file
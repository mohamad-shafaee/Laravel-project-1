<?php

use Illuminate\Support\Facades\Route;
use Summit\Admin\Http\Controllers\AdminController;

/*Route::group(['middleware', ['web']], function(){

Route::middleware('guest')->group(function () {
    

	  Route::prefix('admin')->group(function(){ 

		Route::get('/login', [AdminController::class, 'createLogin'])
		//->defaults('_config', ['view' => 'admin::auth.login'])
		->name('admin.session.create');
		//Logining to site and updating session
		Route::post('/login', [AdminController::class, 'store'])->name('admin-login');

	});



});



});*/


Route::middleware(['web', 'guest'])->group(function () {
    

	  Route::prefix('admin')->group(function(){ 

		Route::get('/login', [AdminController::class, 'createLogin'])
		//->defaults('_config', ['view' => 'admin::auth.login'])
		->name('admin.session.create');
		//Logining to site and updating session
		Route::post('/login', [AdminController::class, 'store'])->name('admin-login');

	});

});




    

	 /* Route::prefix('admin')->group(function(){ 

		Route::get('/login', [AdminController::class, 'createLogin'])->middleware(['web', 'guest'])->name('admin.session.create');
		//Logining to site and updating session
		Route::post('/login', [AdminController::class, 'store'])->name('admin-login');

	});*/







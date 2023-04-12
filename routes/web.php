<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainpageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
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

/*Route::get('/', function () { 
    

    return view('home');
})->name('home');*/

Route::get('/', [MainpageController::class, 'index'])->name('home');
//->middleware(['auth'])->name('home');

Route::get('/post-details/{id}', [PostController::class, 'showPostDetails'])->name('post-details');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/get-user-image/{id}', [PostController::class, 'getUserImage'])->name('get-user-image');


//Exercising download (should be removed)
Route::get('/down', [MainpageController::class, 'download'])->name('download1');


//For creating a post
Route::get('/create-post', [PostController::class, 'create'])->middleware(['auth', 'phone-verified'])->name('create-post');
Route::post('/save-post', [PostController::class, 'save'])->name('save-post'); 
Route::post('/save-post-title', [PostController::class, 'saveTitle'])->name('save-post-title'); 
Route::post('/save-post-description', [PostController::class, 'saveDescription'])->name('save-post-description'); 
Route::post('/save-post-price', [PostController::class, 'savePrice'])->name('save-post-price');
Route::post('/save-post-type', [PostController::class, 'saveType'])->name('save-post-type');
Route::post('/save-post-category', [PostController::class, 'saveCategory'])->name('save-post-category');
Route::post('/save-post-province', [PostController::class, 'saveProvince'])->name('save-post-province');
Route::post('/save-post-city', [PostController::class, 'saveCity'])->name('save-post-city');
Route::post('/save-post-address', [PostController::class, 'saveAddress'])->name('save-post-address');

Route::post('/save-post-images', [PostController::class, 'saveFiles'])->name('save-post-files');

//The deprecated method of deleting images in the edit or create form.
//The array is optional since it is set by javascript and has not value when page is loaded.
//Route::get('/delete-images/{postId}/{array?}', [PostController::class,'deleteImages'])
//->name('delete.post.images');

Route::post('/delete-images/', [PostController::class,'deleteImages'])
->name('delete.post.images');

Route::get('/myprofile', [UserController::class, 'profile'])->middleware('auth')->name('myprofile');
//Route::post('/saveprofile', [UserController::class, 'save'])->middleware('auth')->name('save-profile');
Route::post('/save-username', [UserController::class, 'saveUsername'])->middleware('auth')->name('save-username-profile');

Route::post('/save-useremail', [UserController::class, 'saveUserEmail'])->middleware('auth')->name('save-useremail-profile');

Route::post('/save-usermellicode', [UserController::class, 'saveUserMellicode'])->middleware('auth')->name('save-mellicode-profile');

Route::post('/save-phone', [UserController::class, 'saveUserPhone'])->middleware('auth')->name('save-phone-profile');

Route::post('/save-pass', [UserController::class, 'saveUserPassword'])->middleware('auth')->name('save-password');

Route::post('/save-profile-image', [UserController::class, 'saveImage'])->middleware('auth')->name('save-profile-image');
Route::get('/remove-profile-image', [UserController::class, 'removeImage'])->middleware('auth')->name('remove-profile-image');
Route::get('/myposts', [UserController::class, 'posts'])->middleware('auth')->name('myposts');
Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('user-session-destroy');

//Route::post('/search-res', 'SearchController')->name('search');

Route::get('/search-res', [SearchController::class, 'searchPosts'])->name('search');

Route::get('/delete-post/{id}', [PostController::class, 'delete'])->name('delete-post');

Route::get('/edit-post/{id}', [PostController::class, 'editPage'])->name('post-edit-page');

Route::get('/get-user-name/{id}', [PostController::class, 'getUserName'])->name('get-user-name');
Route::get('/get-post-images/{id}', [PostController::class, 'getPostImages'])->name('get-post-images');


Route::post('/filter_posts', [PostController::class, 'filter'])->name('filter_posts');

Route::get('/user-profile/{user_id}', [UserController::class, 'userProfile'])->name('user-profile');

Route::get('/follow/{observed_id}', [UserController::class, 'follow'])->middleware(['auth'])->name('follow');

Route::get('/unfollow/{followed_id}', [UserController::class, 'unfollow'])->middleware(['auth'])->name('unfollow');



require __DIR__.'/auth.php';

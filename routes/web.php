<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserblockController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\TimelineController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/alert', function (){
    return redirect()->route('home')->with('info', 'You have signed up!');
});

Route::get('/signup', [AuthController::class, 'getSignup'])
->name('auth.signup')
->middleware('guest');

Route::post('/signup', [AuthController::class, 'postSignup'])
->name('auth.signup')
->middleware('guest');

Route::get('/signin', [AuthController::class, 'getSignin'])
->name('auth.signin')
->middleware('guest');

Route::post('/signin', [AuthController::class, 'postSignin'])
->name('auth.signin')
->middleware('guest');

Route::get('/signout', [AuthController::class, 'getSignout'])
->name('auth.signout');

Route::post('/search', [SearchController::class, 'postSearches'])
->name('search.searches');

Route::get('/userblock/{username}', [UserblockController::class, 'getIndex'])
->name('userblock.index')
->middleware('auth');

Route::get('/myprofile/update', [MyProfileController::class, 'getUpdate'])
->name('myprofile.update');
Route::post('/myprofile/update', [MyProfileController::class, 'postUpdate'])
->name('myprofile.update');

Route::get('/friends', [FriendController::class, 'getIndex'])
->name('friends.index');
Route::get('/friends/add/{username}', [FriendController::class, 'getAddFriend'])
->name('friends.addFriend');
Route::get('/friends/accept/{username}', [FriendController::class, 'getAcceptFriend'])
->name('friends.acceptFriend');



Route::get('/timeline', [TimelineController::class, 'index'])
->name('timeline.index');
Route::post('/timeline/update-status', [TimelineController::class, 'postUpdateStatus'])
->name('timeline.updateStatus');

Route::get('/timeline/status/{statusId}/like', [TimelineController::class, 'getLike'])
->name('timeline.status.like');
Route::post('/timeline/status/{statusId}/reply', [TimelineController::class, 'postReply'])
->name('timeline.status.reply');




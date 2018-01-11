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

Route::redirect('/', 'threads');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('threads', 'ThreadsController@index')->name('threads');
Route::get('threads/create', 'ThreadsController@create')->middleware('must-be-confirmed')->name('threads.create');
Route::get('threads/search', 'SearchController@show')->name('search.show');
Route::get('threads/{channel}/{thread}', 'ThreadsController@show')->name('threads.show');
Route::patch('threads/{channel}/{thread}', 'ThreadsController@update')->name('threads.update');
Route::delete('threads/{channel}/{thread}', 'ThreadsController@destroy')->name('threads.destroy');
Route::post('threads', 'ThreadsController@store')->middleware('must-be-confirmed')->name('threads.store');
Route::get('threads/{channel}', 'ThreadsController@index')->name('channels');

Route::post('locked-threads/{thread}', 'LockedThreadsController@store')->name('locked-threads.store')->middleware('admin');
Route::delete('locked-threads/{thread}', 'LockedThreadsController@destroy')->name('locked-threads.destroy')->middleware('admin');

Route::get('/threads/{channel}/{thread}/replies', 'RepliesController@index')->name('replies');
Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store')->middleware('must-be-confirmed')->name('replies.store');
Route::patch('/replies/{reply}', 'RepliesController@update')->name('replies.update');
Route::delete('/replies/{reply}', 'RepliesController@destroy')->name('replies.destroy');

Route::post('/replies/{reply}/best', 'BestRepliesController@store')->name('best-replies.store');

Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store')->middleware('auth')->name('threads.store');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy')->middleware('auth')->name('threads.destroy');

Route::post('/replies/{reply}/favorites', 'FavoritesController@store')->name('replies.favorite');
Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy')->name('replies.unfavorite');

Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');
Route::get('/profiles/{user}/notifications', 'UserNotificationsController@index')->name('user-notifications');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy')->name('user-notification.destroy');

Route::get('/register/confirm', 'Auth\RegisterConfirmationController@index')->name('register.confirm');

Route::get('api/users', 'Api\UsersController@index')->name('api.users');
Route::post('api/users/{user}/avatar', 'Api\UserAvatarController@store')->middleware('auth')->name('avatar');

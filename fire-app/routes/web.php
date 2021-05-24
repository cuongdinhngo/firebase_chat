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

Auth::routes();

Route::get("/", function(){
    return redirect()->route('messages.index');
});

Route::get("/home", function(){
    return redirect()->route('messages.index');
});

Route::group(["middleware" => ['auth'], "prefix" => "messages", "namespace" => "Message", "as" => "messages."], function () {
    Route::get("/public", "MessageController@index")->name('index');
    Route::post("/chat", "MessageController@store")->name('chat');
});

Route::group(["prefix" => "users", "namespace" => "User", "as" => "users."], function () {
    Route::get("/{id}", "UserController@getUser")->name('get-user');
    Route::get("/connect/{id}", "UserController@connect")->name('connect');
    Route::get("/notifications/list", "UserController@listNotifications")->name("list-notifications");
    Route::post("/save-token", "UserController@saveToken")->name('save-token');
});

Route::group(["middleware" => ['auth'], "prefix" => "rooms", "namespace" => "Room", "as" => "rooms."], function () {
	Route::get("/", "RoomController@enterRoom")->name('enter');
});

Route::group(["middleware" => ['auth'], "prefix" => "status", "namespace" => "UserStatus", "as" => "status."], function () {
	Route::get("/", "UserStatusController@index")->name('index');
});

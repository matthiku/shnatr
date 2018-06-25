<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Register API routes for our application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/


/**
 * Auth Routes for Guests *
 */
Route::group(
    ['middleware' => 'guest:api'], function () {
        Route::post('login', 'Auth\LoginController@login');
        Route::post('register', 'Auth\RegisterController@register');
        Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/reset', 'Auth\ResetPasswordController@reset');

        Route::post('oauth/{driver}', 'Auth\OAuthController@redirectToProvider');
        Route::get('oauth/{driver}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback');
    }
);


/**
 * Auth Routes for Authenticated Users *
 */
Route::group(
    ['middleware' => 'auth:api'], function () {
        Route::post('logout', 'Auth\LoginController@logout');

        Route::get(
            '/user', function (Request $request) {
                return $request->user();
            }
        );

        Route::patch('settings/profile', 'Settings\ProfileController@update');
        Route::patch('settings/password', 'Settings\PasswordController@update');

        // send email address verification link again
        Route::get('sendverifyemail', 'Auth\VerifyController@sendVerifyEmail');

        // get a simple list of all users
        Route::get('users', 'Rooms\RoomActivitiesController@userList')
            ->middleware('checkVerified');
    }
);


/**
 * Routes for Chat Rooms and Messages
 */
Route::group(['middleware' => ['auth:api', 'checkVerified']], function () {

    // create a new chat room
    Route::post('rooms', 'Rooms\CreateRoomController@store');

    // delete a chat room
    Route::delete('rooms', 'Rooms\DeleteRoomController@destroy');

    // get a list of all rooms
    Route::get('rooms', 'Rooms\ListRoomsController@roomList');

    // get a single rooms
    Route::get('rooms/{room}', 'Rooms\ListRoomsController@singleRoom');

    // set email notification setting per user per room
    Route::post('rooms/{room}/setemailnotification', 'Rooms\UpdateRoomController@setemailnotification');

    // allow user (room member) to leave a room
    Route::post('rooms/{room}/leave', 'Rooms\RoomActivitiesController@leaveRoom');

    // set reading progress of a user in a room
    Route::post('rooms/{room}/setreading', 'Rooms\RoomActivitiesController@setreading');

    // indicate that a user starts typing a message in a room
    Route::post('rooms/{room}/typing', 'Rooms\RoomActivitiesController@typing');

    // getLatestFrontendVersion
    Route::get('getlatestfrontendversion', 'Rooms\ListRoomsController@getLatestFrontendVersion');

});

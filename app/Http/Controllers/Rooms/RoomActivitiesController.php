<?php
/**
 * Various Chat Room Activities
 * 
 * PHP version 7.x
 * 
 * @category  Controller
 * @package   Shnatr
 * @author    Matthias Kuhs <matthiku@yahoo.com>
 * @copyright 2018 Matthias Kuhs
 * @license   MIT http://mit.org
 * @link      http://github.org/matthiku/shnatr
 */

namespace App\Http\Controllers\Rooms;

use DB;
use Auth;
use App\User;
use App\Room;
use App\Events\RoomTyping;
use App\Events\RoomUpdated;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * RoomActivitiesController
 * 
 * @category Controller_Class
 * @package  Shnatr
 * @author   Matthias Kuhs <matthiku@yahoo.com>
 * @license  MIT http://mit.org
 * @link     http://github.org/matthiku/shnatr
 */
class RoomActivitiesController extends Controller
{


    /**
     * Get a List of all Users.
     *
     * @return \Illuminate\Http\Response
     */
    public function userList()
    {
        // only return certain fields
        $users = User::select('id', 'name')->get();
        return $users;
    }




    /**
     * Allow a user to leave a chat room
     *
     * @param \App\Room $room Model
     *
     * @return \Illuminate\Http\Response
     */
    public function leaveRoom(Room $room)
    {
        // get current user
        $user = Auth::user();

        // the owner cannot desert his own room
        if ($user->isOwner($room)) {
                return 'failed';
        }

        // remove this user from the chat room
        $room->users()->detach($user->id);

        // create a new broadcasted for this event
        broadcast(new RoomUpdated($room, $user));

        return 'You have left this room!';
    }


    /**
     * Set reading progress of a user in a room
     *
     * @param \App\Room $room Model
     *
     * @return \Illuminate\Http\Response
     */
    public function setreading(Room $room)
    {
        // get current user
        $user = Auth::user();

        // Set the update_at date in the pivot table
        // to indicate the reading progress of the user in this room
        $membership = $room->users()->where('user_id', $user->id)->first();
        $membership->pivot->touch();

        // inform all subscribers of this change
        broadcast(new RoomUpdated($room, $user));

        return response($room);
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Room $room Model
     *
     * @return \Illuminate\Http\Response
     */
    public function typing(Room $room)
    {
        // get current users and send the 'typing' broadcast
        $user = Auth::user();
        broadcast(new RoomTyping($room, $user));

        return response(
            [
                'status' => 'OK',
                'frontendVersion' => filemtime(base_path().'/public/js/app.js')
            ]
        );
    }

}

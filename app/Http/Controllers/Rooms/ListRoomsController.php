<?php
/**
 * List Rooms Data
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

use Auth;
use App\Room;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * ListRoomsController
 * 
 * @category Controller
 * @package  Shnatr
 * @author   Matthias Kuhs <matthiku@yahoo.com>
 * @license  MIT http://mit.org
 * @link     http://github.org/matthiku/shnatr
 */
class ListRoomsController extends Controller
{

    /**
     * Get latest frontend source code date
     *
     * @return \Illuminate\Http\Response
     */
    public function getLatestFrontendVersion()
    {
        return response(
            [
                'status' => 'OK',
                'frontendVersion' => filemtime(base_path().'/public/js/app.js')
            ]
        );
    }


    /**
     * Get a list of all chatrooms a user is member of
     *
     * @return \Illuminate\Http\Response
     */
    public function roomList()
    {
        // get current user
        $user = Auth::user();

        // get a list of all chat rooms this user is member of and
        // make sure the most recently updated room is coming first
        $rooms = $user->memberships;

        // get the members and messages of each chat
        foreach ($rooms as $key => $rm) {
            $room = Room::find($rm->id);
            $rm->users = $room->users;
            $rm->messages = $room->messages;
        }
        return $rooms;
    }



    /**
     * Get a single Room.
     *
     * @param \App\Room $room Model
     *
     * @return \Illuminate\Http\Response
     */
    public function singleRoom(Room $room)
    {
        // get another room object
        $rm = Room::find($room->id);
        $rm->messages = $room->messages;
        return $rm;
    }


}

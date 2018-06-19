<?php
/**
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
use App\Events\RoomCreated;
use Illuminate\Http\Request;

/**
 * CreateRoomController
 * 
 * @category Controller Class
 * @package  Shnatr
 * @author   Matthias Kuhs <matthiku@yahoo.com>
 * @license  MIT http://mit.org
 * @link     http://github.org/matthiku/shnatr
 */
class CreateRoomController extends Controller
{


  /**
   * Create a new chat room and attach the members
   *
   * @param \Illuminate\Http\Request $request the HTTP request data
   *
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    // get current user
    $user = Auth::user();

    // create a new room with an optional name
    $room = new Room();
    if ($request->has('name')) {
      $room->name = $request->name;
    }
    // define the owner of the new room
    $user->rooms()->save($room);

    // also make the owner a member (other members can see the list of members)
    $room->users()->attach(
        $user->id,
        ['email_notification' => $request->email_notification]
    );

    // add other members to this chat room
    if ($request->has('members')) {
        $room->users()->attach(
            $request->members,
            ['email_notification' => true] // default to send email notif.
        );
    }

    // create a new broadcasted for this event
    broadcast(new RoomCreated($room, $user));
    
    return $room;
  }

}

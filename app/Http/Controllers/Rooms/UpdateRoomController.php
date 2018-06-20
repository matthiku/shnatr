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
use App\Events\RoomUpdated;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


/**
 * UpdateRoomController
 * 
 * @category Controller Class
 * @package  Shnatr
 * @author   Matthias Kuhs <matthiku@yahoo.com>
 * @license  MIT http://mit.org
 * @link     http://github.org/matthiku/shnatr
 */
class UpdateRoomController extends Controller
{


  /**
   * Change email notification setting of a user for new messages in a room
   *
   * @param \App\Room                $room    Room Model
   * @param \Illuminate\Http\Request $request HTTP data
   *
   * @return \Illuminate\Http\Response
   */
  public function setemailnotification(Room $room, Request $request)
  {

    // get current user
    $user = Auth::user();

    $user->memberships()
      ->updateExistingPivot(
        $room->id,
        ['email_notification' => $request->emailNotification]
      );

    return $room;
  }



  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request HTTP data
   * @param \App\Room                $room    Model
   *
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Room $room)
  {
    // get current user
    $user = Auth::user();

    // check if user owns this room
    if (! $user->isOwner($room)) {
      return 'failed';
    }

    // update optional chatroom name
    if ($request->has('name')) {
      $room->name = $request->name;
      $room->save();
    }

    // attach/detach members to this chat room according to the request
    $existingUsers = $room->users->pluck('id')->all();
    $requestedUsers = $request->members;

    // do we have a new user for this room?
    foreach ($requestedUsers as $member) {
      if (!in_array($member, $existingUsers)) {
          // To indicate the reading progress of this user in this room,
          // set update_at in the pivot table to the creation date of this room
          $room->users()->attach($member, ['updated_at' => $room->created_at]);
      }
    }

    // Was there a user removed from this room?
    foreach ($existingUsers as $member) {
      if (!in_array($member, $requestedUsers)) {
          $room->users()->detach($member);
      }
    }

    // In any case, add the current user to the list of members
    if (! in_array($user->id, $request->members)) {
      $room->users()->attach($user->id); 
    }

    // create a new broadcasted for this event
    broadcast(new RoomUpdated($room, $user));

    return $room;
  }

}

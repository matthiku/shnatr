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
use App\Events\RoomDeleted;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * DeleteRoomController
 * 
 * @category Controller Class
 * @package  Shnatr
 * @author   Matthias Kuhs <matthiku@yahoo.com>
 * @license  MIT http://mit.org
 * @link     http://github.org/matthiku/shnatr
 */
class DeleteRoomController extends Controller
{


  /**
   * Remove the specified resource from storage.
   *
   * @param \App\Room $room Model
   *
   * @return \Illuminate\Http\Response
   */
  public function destroy(Room $room)
  {
    // check if room exists and user owns this room
    if (!$room || $room->owner->id !== Auth::user()->id) {
      return;
    }

    // broadcast the deletion event
    \Log::info('RoomDeleted event prepared! id: ' . $room->id);
    broadcast(new RoomDeleted($room->id));

    // first, remove all attached users (chatroom members)
    $room->users()->detach();
    // delete all attached files to those messages
    $room->messages->map(
      function ($message, $key) {
        if ($message->filename) {
          $path = public_path().'/images/';
          unlink($path.$message->filename);
        }
      }
    );

    // then delete all related messages
    $room->messages()->delete();

    // now delete the room
    $room->delete();

    return 'deleted!';
  }

}

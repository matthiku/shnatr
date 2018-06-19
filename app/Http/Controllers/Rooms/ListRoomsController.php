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
use Illuminate\Http\Request;

/**
 * ListRoomsController
 * 
 * @category Controller Class
 * @package  Shnatr
 * @author   Matthias Kuhs <matthiku@yahoo.com>
 * @license  MIT http://mit.org
 * @link     http://github.org/matthiku/shnatr
 */
class ListRoomsController extends Controller
{


  /**
   * Get a list of all chatrooms a user is member of
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    // get current user
    $user = Auth::user();

    // get list of all chat rooms this user is member of and
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

}

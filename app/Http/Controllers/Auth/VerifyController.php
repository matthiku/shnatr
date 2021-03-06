<?php
/**
 * Email Verification Controller
 * 
 * PHP version 7
 * 
 * @category Controller
 * @package  Shnatr
 * @author   Matthias Kuhs <matthiku@yahoo.com>
 * @license  MIT http://mit.org
 * @link     http://github.org/matthiku/shnatr
 */

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Handles Email Address Verification
 * 
 * @category  Class
 * @package   Shnatr
 * @author    Matthias Kuhs <matthiku@yahoo.com>
 * @copyright 2018 Matthias Kuhs
 * @license   MIT http://mit.org
 * @link      http://github.org/matthiku/shnatr
 */
class VerifyController extends Controller
{
    /**
     * Verify the email address of a new user
     * 
     * @param string $token Token as provided by the verification email
     * 
     * @return redirect
     */
    public function verifyEmail($token)
    {
        $user = User::where('verifyToken', $token)->first();

        $status = 'auth.invalid_account_verification';
        if ($user) {
            $user->update(['verifyToken' => null]); // verify the user
            $status = 'auth.account_verified';
        }

        return redirect()
            ->route('home', 'home')
            ->with('status', trans($status));
    }


    /**
     * Send verification email again
     * 
     * @return redirect
     */
    public function sendVerifyEmail()
    {
        $user = Auth::user();

        if (!$user->isVerified()) {
            $user->sendVerificationEmail();
        }

        return response(
            [
                'status' => 'OK',
                'user' => $user
            ]
        );
    }
}

<?php
/**
 * USER model
 * 
 * PHP version 7
 * 
 * @category Eloquent_Model
 * @package  Shnatr
 * @author   Matthias Kuhs <matthiku@yahoo.com>
 * @license  MIT http://mit.org
 * @link     http://github.org/matthiku/shnatr
 */

namespace App;

use App\Notifications\VerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;

/**
 * Describes the USER Model
 * 
 * @category  Model
 * @package   Shnatr
 * @author    Matthias Kuhs <matthiku@yahoo.com>
 * @copyright 2018 Matthias Kuhs
 * @license   MIT http://mit.org
 * @link      http://github.org/matthiku/shnatr
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'verifyToken'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'photo_url',
    ];

    /**
     * Get the profile photo URL attribute.
     *
     * @return string
     */
    public function getPhotoUrlAttribute()
    {
        return 'https://www.gravatar.com/avatar/'.md5(strtolower($this->email)).'.jpg?s=200&d=mm';
    }


    /**
     * Get the oauth providers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function oauthProviders()
    {
        return $this->hasMany(OAuthProvider::class);
    }


    /**
     * Send the password reset notification.
     *
     * @param string $token Token as provided by the verification email
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }


    /**
     * Get JWT identifier
     *
     * @return int
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get JWT custom claims
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    /**
     * Returns true if the user's email has been verified
     * 
     * (during registration, a random token is generated.
     * This token will be removed after the user clicked on the link
     * in the Email Address Verification notification.)
     * 
     * @return bool user is verified
     */
    public function isVerified()
    {
        return $this->verifyToken === null;
    }


    /**
     * Send email address verification email
     * 
     * @return void
     */
    public function sendVerificationEmail()
    {
        $this->notify(new VerifyEmail($this));
    }

}

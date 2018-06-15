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

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Send Email Address Verification Notification
 * 
 * @category  Class
 * @package   Shnatr
 * @author    Matthias Kuhs <matthiku@yahoo.com>
 * @copyright 2018 Matthias Kuhs
 * @license   MIT http://mit.org
 * @link      http://github.org/matthiku/shnatr
 */
class VerifyEmail extends Notification
{
    use Queueable;

    public $user;

    /**
     * Create a new notification instance.
     *
     * @param string $user User model instance
     * 
     * @return void
     */
    public function __construct(User $user)
    {
        // assign the user
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable Notifiable?
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable Notifiable?
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line(__('Please verify your email address to continue') . '.')
                    ->action(
                        __('Verify Account'), 
                        route('verify', $this->user->verifyToken)
                    )
                    ->line(__('Enjoy using Shnatr') . '!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable Notifiable?
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

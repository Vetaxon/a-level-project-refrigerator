<?php

namespace App\Mail;

use App\Order;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $mailMessage;
    public $password;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $mailMessage
     * @param string $password
     */
    public function __construct(User $user, string $mailMessage = '', $password = null)
    {
        $this->user = $user;
        $this->mailMessage = $mailMessage;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_USERNAME'))
            ->markdown('emails.orders.register')
            ->with(['user' => $this->user])
            ->with(['password' => $this->password])
            ->with(['mailMessage' => $this->mailMessage]);
    }
}

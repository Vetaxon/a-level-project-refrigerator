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
     * @return void
     */
    public function __construct(User $user, string $mailMessage = '', string $password = '')
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
        return $this->from('postmaster@sandboxa62384ba47bc4c8fbf2023fe04dbe3bb.mailgun.org')
            ->view('emails.register')
            ->with(['user' => $this->user]); //, 'password' => $this->password]);
    }
}

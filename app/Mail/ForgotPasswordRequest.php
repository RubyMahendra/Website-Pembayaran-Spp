<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ForgotPasswordRequest extends Mailable
{
    public $username;
    public $email;
    public $oldPassword;
    public $newPassword;

    public function __construct($username, $email, $oldPassword, $newPassword)
    {
        $this->username = $username;
        $this->email = $email;
        $this->oldPassword = $oldPassword;
        $this->newPassword = $newPassword;
    }

    public function build()
    {
        return $this->subject('Permintaan Lupa Password')
                    ->view('emails.forgot-password');  // Pastikan view ini sudah dibuat
    }
}

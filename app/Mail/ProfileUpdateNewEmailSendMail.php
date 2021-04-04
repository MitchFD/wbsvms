<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProfileUpdateNewEmailSendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
    public $old_profile;
    public $new_profile;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details, $old_profile ,$new_profile)
    {
        $this->details = $details;
        $this->old_profile = $old_profile;
        $this->new_profile = $new_profile;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Email Registered')->view('emails.profile_update_new_email_mail');
    }
}

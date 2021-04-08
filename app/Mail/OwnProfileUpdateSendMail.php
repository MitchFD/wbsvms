<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OwnProfileUpdateSendMail extends Mailable
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
        return $this->subject('Profile Update')->view('emails.own_profile_update_mail');
    }
}

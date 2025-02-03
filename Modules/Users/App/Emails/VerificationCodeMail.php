<?php

namespace Modules\Users\App\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Users\App\Models\User;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     * @param User $user
     * @param integer $code
     */
    public function __construct(
        public User $user,
        public $code
    ) {}

    /**
     * Build the message.
     * @return self
     */
    public function build(): self
    {
        return $this->subject(__('users::auth.email_verification_code'))
            ->markdown('users::emails.verification_code')
            ->with([
                'code' => $this->code,
                'name'  => $this->user->full_name
            ]);
    }
}

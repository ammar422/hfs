<?php

namespace Modules\Users\App\Jobs;

use Illuminate\Bus\Queueable;
use Modules\Users\App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Users\App\Emails\VerificationCodeMail;

class SendVerificationCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var User
     */
    protected User $user;
    /**
     * Create a new job instance.
     *
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $code = rand(100000, 999999);
        $this->user->verification_code = $code;
        $this->user->account_status = 'pending';
        $this->user->save();
        Mail::to($this->user->email)->send(new VerificationCodeMail($this->user, $code));
    }
}

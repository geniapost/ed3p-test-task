<?php

namespace app\Listeners\User;

use app\Events\User\UserCreated;
use App\Services\MailService;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreatedListener implements ShouldQueue
{
    protected MailService $mailService;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        $this->mailService = new MailService();
    }

    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void
    {
        $user = $event->user;
        $this->mailService::sendUserCreatedMail($user);
    }
}

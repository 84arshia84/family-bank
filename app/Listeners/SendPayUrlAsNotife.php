<?php

namespace App\Listeners;

use App\Events\InstallmentHasBeenDeferred;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPayUrlAsNotife
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(InstallmentHasBeenDeferred $event): void
    {
        Notification::make([
            'date' => now()->toDateString(),
            'text' => 'text',
            'user_id' => $event->installment->loan->user_id
        ])->setUrl($event->installment->id);
    }
}

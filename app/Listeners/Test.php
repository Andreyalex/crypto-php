<?php

namespace App\Listeners;

use App\Events\ExtraHighVolume;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class Test
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ExtraHighVolume  $event
     * @return void
     */
    public function handle(ExtraHighVolume $event)
    {
        //
    }
}

<?php

namespace App\Listeners;

use App\Events\SocketTest;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SocketTestPush
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
     * @param  SocketTest  $event
     * @return void
     */
    public function handle(SocketTest $event)
    {
        //
    }
}

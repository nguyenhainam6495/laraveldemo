<?php

namespace App\Listeners;

use App\Events\CountView;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class CountViewListener
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
     * @param  CountView  $event
     * @return void
     */
    public function handle(CountView $event)
    {
        Log::info('ahaha');
    }
}

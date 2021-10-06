<?php

namespace App\Listeners\Auth;

use App\Services\Bitcoind;
// use Log;
use App\Services\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogUserRegistered
{

    /**
     * @var App\Services\UserService|null
     */
    private $userService;

    /**
     * Create the event listener.
     *
     * @param App\Services\UserService $userService
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if (!$this->userService->hasWalletAddress($event->user)) {
            // Log::info(print_r($event->user));

            $this->userService->createWalletAddress($event->user);
        }
    }
}

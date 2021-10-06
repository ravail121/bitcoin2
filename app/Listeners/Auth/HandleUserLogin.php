<?php

namespace App\Listeners\Auth;

use App\Services\UserService;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleUserLogin
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
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        if ($event->guard !== 'admin' && !$this->userService->hasWalletAddress($event->user)) {
            $this->userService->createWalletAddress($event->user);
        }
    }
}

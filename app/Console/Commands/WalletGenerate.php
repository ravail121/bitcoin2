<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Console\Command;

class WalletGenerate extends Command
{
    /**
     * @var App\Services\UserService|null
     */
    private $userService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallet:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new wallet address';

    /**
     * Create a new command instance.
     *
     * @param App\Services\UserService $userService
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        parent::__construct();

        $this->userService = $userService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        User::chunk(100, function ($users) {
            foreach ($users as $user) {
                if (!$this->userService->hasWalletAddress($user)) {
                    $this->userService->createWalletAddress($user);
                }
            }
        });
    }
}

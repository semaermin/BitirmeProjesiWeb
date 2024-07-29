<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class GenerateUserUUIDs extends Command
{
    protected $signature = 'generate:user-uuids';
    protected $description = 'Generate UUIDs for existing users';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        User::whereNull('uuid')->get()->each(function ($user) {
            $user->uuid = (string) \Illuminate\Support\Str::uuid();
            $user->save();
        });

        $this->info('UUIDs generated for users.');
    }
}

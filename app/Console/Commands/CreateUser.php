<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Balance;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    
    protected $signature = 'user:create {name} {email} {password}';    

    /**
     * The console command description.
     *
     * @var string
     */
    
    protected $description = 'Создать нового пользователя';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::create([
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
            'password' => Hash::make($this->argument('password'))            
        ]);

        Balance::firstOrCreate(['user_id' => $user->id]);

        $this->info("Пользователь {$user->email} создан.");
    }
}

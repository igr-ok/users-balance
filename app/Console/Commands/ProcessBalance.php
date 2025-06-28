<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Balance;


class ProcessBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    
    protected $signature = 'balance:process
        {email : Email пользователя} 
        {amount : Сумма, положительная или отрицательная} 
        {description : Описание операции}
        {--debit : Флаг для списания баланса}';

    /**
     * The console command description.
     *
     * @var string
     */
    
    protected $description = 'Начисление или списание с баланса пользователя';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (!$user) {
            $this->error('Пользователь не найден.');
            return;
        }

        $balance = Balance::firstOrCreate(
            ['user_id' => $user->id],
            ['amount' => 0]
        );

        $rawAmount = floatval($this->argument('amount'));
        $description = $this->argument('description');        
        $isDebit = $this->option('debit');

        if ($rawAmount <= 0) {
            $this->error('Сумма должна быть положительной.');
            return;
        }        

        $amount = $isDebit ? -$rawAmount : $rawAmount;

        $newAmount = $balance->amount + $amount;

        if ($newAmount < 0) {
            $this->error("Недостаточно средств. Текущий баланс: {$balance->amount}, требуется: " . abs($amount));
            return;
        }

        \App\Jobs\ProcessBalanceJob::dispatch($user->id, $rawAmount, $description, $isDebit);

        $this->info("Операция поставлена в очередь для пользователя {$user->email}.");
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Balance;
use App\Models\Operation;
use Illuminate\Support\Facades\DB;

class ProcessBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //protected $signature = 'app:process-balance';
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
    //protected $description = 'Command description';
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

        $amount = floatval($this->argument('amount'));
        $description = $this->argument('description');
        //$type = $amount > 0 ? 'credit' : 'debit';
        $isDebit = $this->option('debit');

        if ($amount <= 0) {
            $this->error('Сумма должна быть положительной.');
            return;
        }

        $amount = $isDebit ? -$amount : $amount;
        $type = $isDebit ? 'debit' : 'credit';

        DB::beginTransaction();

        try {
            $newAmount = $balance->amount + $amount;

            if ($newAmount < 0) {
                $this->error('Недостаточно средств.');
                DB::rollBack();
                return;
            }

            $balance->update(['amount' => $newAmount]);

            Operation::create([
                'user_id' => $user->id,
                'type' => $type,
                'amount' => abs($amount),
                'description' => $description,
            ]);

            DB::commit();

            $this->info("Операция '{$type}' выполнена. Новый баланс: {$balance->amount}");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Ошибка: " . $e->getMessage());
        }
    }
}

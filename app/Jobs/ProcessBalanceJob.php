<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\User;
use App\Models\Balance;
use App\Models\Operation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class ProcessBalanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $userId;
    protected float $amount;
    protected string $description;
    protected bool $isDebit;

    /**
     * Create a new job instance.
     */
    public function __construct(int $userId, float $amount, string $description, bool $isDebit = false)
    {
        $this->userId = $userId;
        $this->amount = $amount;
        $this->description = $description;
        $this->isDebit = $isDebit;        
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::transaction(function () {
            $user = User::findOrFail($this->userId);

            $balance = Balance::firstOrCreate(
                ['user_id' => $user->id],
                ['amount' => 0]
            );

            if ($this->amount <= 0) {
                throw new \Exception('Сумма должна быть положительной.');
            }

            $finalAmount = $this->isDebit ? -$this->amount : $this->amount;
            $type = $this->isDebit ? 'debit' : 'credit';
            $newAmount = $balance->amount + $finalAmount;

            if ($newAmount < 0) {
                throw new \Exception("Недостаточно средств для пользователя {$user->email}");
            }

            $balance->update(['amount' => $newAmount]);

            Operation::create([
                'user_id' => $user->id,
                'type' => $type,
                'amount' => abs($finalAmount),
                'description' => $this->description,
            ]);
        });
    }
}

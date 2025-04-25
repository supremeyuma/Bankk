<?php

namespace App\Services;

use App\Models\Account;
use Exception;
use Illuminate\Support\Facades\DB;

class TransferService
{
    public function transferBetweenOwnAccounts(Account $senderAccount, Account $recipientAccount, float $amount): bool
    {
        $user = auth()->user();

        return DB::transaction(function () use ($user, $senderAccount, $recipientAccount, $amount) {
            if ($senderAccount->user_id !== $user->id || $recipientAccount->user_id !== $user->id) {
                throw new \Exception('You can only transfer between your own accounts.');
            }

            if ($senderAccount->balance < $amount) {
                throw new \Exception('Insufficient funds.');
            }

            $senderAccount->decrement('balance', $amount);
            $recipientAccount->increment('balance', $amount);

            $senderAccount->transactions()->create([
                'type' => 'internal_transfer',
                'amount' => $amount,
                'currency' => 'USD',
                'recipient_account_id' => $recipientAccount->account_number,
                'description' => 'Transfer to ' . $recipientAccount->account_number,
            ]);

            $recipientAccount->transactions()->create([
                'type' => 'internal_transfer',
                'amount' => $amount,
                'currency' => 'USD',
                'sender_account_id' => $senderAccount->account_number,
                'description' => 'Transfer from ' . $senderAccount->account_number,
            ]);

            return true;
        });
    }



    public function transferToOtherUsers(Account $senderAccount, Account $recipientAccount, float $amount): bool
    {
        $user = auth()->user();

        return DB::transaction(function () use ($user, $senderAccount, $recipientAccount, $amount) {
            if ($senderAccount->user_id === $recipientAccount->user_id) {
                throw new \Exception('Use self-transfer to send to your own accounts.');
            }

            if ($senderAccount->balance < $amount) {
                throw new \Exception('Insufficient funds.');
            }

            $senderAccount->decrement('balance', $amount);
            $recipientAccount->increment('balance', $amount);

            $senderAccount->transactions()->create([
                'type' => 'internal_transfer',
                'amount' => $amount,
                'currency' => 'USD',
                'recipient_account_id' => $recipientAccount->account_number,
                'description' => 'Transfer to ' . $recipientAccount->account_number,
            ]);

            $recipientAccount->transactions()->create([
                'type' => 'internal_transfer',
                'amount' => $amount,
                'currency' => 'USD',
                'sender_account_id' => $senderAccount->account_number,
                'description' => 'Transfer from ' . $senderAccount->account_number,
            ]);

            return true;
        });
    }

}

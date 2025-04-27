<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transfer;

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

            Transfer::create([
                'sender_account_id' => $senderAccount->id,
                'recipient_account_id' => $recipientAccount->id,
                'amount' => $amount,
                'type' => 'self',
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

            $recipientName = $recipientAccount->user->name ?? 'Unknown User';
            $senderName = $senderAccount->user->name ?? 'Unknown User';



            $senderAccount->transactions()->create([
                'type' => 'internal_transfer',
                'flow' => 'out',
                'amount' => $amount,
                'currency' => 'USD',
                'recipient_account_id' => $recipientAccount->id,
                'description' => 'Transfer to ' . $recipientName,
            ]);

            $recipientAccount->transactions()->create([
                'type' => 'internal_transfer',
                'flow' => 'in',
                'amount' => $amount,
                'currency' => 'USD',
                'sender_account_id' => $senderAccount->id,
                'description' => 'Transfer from ' . $senderName,
            ]);

            Transfer::create([
                'sender_account_id' => $senderAccount->id,
                'recipient_account_id' => $recipientAccount->id,
                'amount' => $amount,
                'type' => 'others',
            ]);

            return true;
        });
    }

}

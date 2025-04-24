<?php

namespace App\Services;

use App\Models\Account;
use Exception;

class TransferService
{
    public function transferFunds(Account $senderAccount, Account $recipientAccount, float $amount): bool
    {
        $user = auth()->user();

        // Ensure both sender and recipient belong to the same user for self transfer
        if ($senderAccount->user_id !== $user->id || $recipientAccount->user_id !== $user->id) {
            throw new Exception('You can only transfer between your own accounts.');
        }

        // Check if sender has enough funds
        if ($senderAccount->balance < $amount) {
            throw new Exception('Insufficient funds.');
        }

        // Deduct from sender account and add to recipient account
        $senderAccount->balance -= $amount;
        $recipientAccount->balance += $amount;

        // Save updated accounts
        $senderAccount->save();
        $recipientAccount->save();

        // Log the transactions
        $senderAccount->transactions()->create([
            'type' => 'internal_transfer',
            'amount' => $amount,
            'currency' => 'USD',
            'recipient_account_id' => $recipientAccount->id,
            'description' => 'Transfer to ' . $recipientAccount->account_number,
        ]);

        $recipientAccount->transactions()->create([
            'type' => 'internal_transfer',
            'amount' => $amount,
            'currency' => 'USD',
            'recipient_account_id' => null, // No recipient for the sender
            'description' => 'Transfer from ' . $senderAccount->account_number,
        ]);

        return true;
    }
}

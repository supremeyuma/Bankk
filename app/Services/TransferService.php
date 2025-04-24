<?php

namespace App\Services;

use App\Models\Account;
use Exception;

class TransferService
{
    /**
     * Handle the internal transfer between accounts.
     *
     * @param Account $senderAccount
     * @param Account $recipientAccount
     * @param float $amount
     * @return bool
     * @throws Exception
     */
    public function transferFunds(Account $senderAccount, Account $recipientAccount, float $amount): bool
    {
        $user = auth()->user();

        // Make sure both accounts belong to the same user
        if ($senderAccount->user_id !== $user->id || $recipientAccount->user_id !== $user->id) {
            throw new Exception('You can only transfer between your own accounts.');
        }

        // Check if sender has enough funds
        if ($senderAccount->balance < $amount) {
            throw new Exception('Insufficient funds');
        }

        // Deduct from sender account and add to recipient account
        $senderAccount->balance -= $amount;
        $recipientAccount->balance += $amount;

        // Save updated accounts
        $senderAccount->save();
        $recipientAccount->save();

        // Log the transactions (optional, but good practice)
        $senderAccount->transactions()->create([
            'type' => 'internal_transfer',
            'amount' => $amount,
            'currency' => 'USD',  // Change if supporting multiple currencies
            'recipient_account_id' => $recipientAccount->id,
            'description' => 'Internal Transfer to ' . $recipientAccount->account_number,
        ]);

        $recipientAccount->transactions()->create([
            'type' => 'internal_transfer',
            'amount' => $amount,
            'currency' => 'USD',
            'recipient_account_id' => null,  // No recipient for the sender
            'description' => 'Internal Transfer from ' . $senderAccount->account_number,
        ]);

        return true;
    }
}

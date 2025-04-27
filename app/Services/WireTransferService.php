<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Exception;

class WireTransferService
{
    public function wireTransfer(array $data)
    {
        $user = auth()->user();

        // Fetch the sender's account
        $senderAccount = Account::where('id', $data['sender_account_id'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($senderAccount->balance < $data['amount']) {
            throw new Exception('Insufficient balance.');
        }

        return DB::transaction(function () use ($senderAccount, $data) {
            // Deduct balance
            $senderAccount->decrement('balance', $data['amount']);

            // Save the transaction
            Transaction::create([
                'account_id' => $senderAccount->id,
                'type' => $data['transfer_type'] === 'domestic' ? 'domestic_wire' : 'international_wire',
                'flow' => 'out',
                'amount' => $data['amount'],
                'currency' => 'USD',
                'description' => 'Wire transfer to ' . $data['recipient_account_name'],
                'recipient_details' => [
                    'bank_name' => $data['recipient_bank_name'],
                    'bank_country' => $data['recipient_bank_country'],
                    'account_number' => $data['recipient_account_number'],
                    'account_name' => $data['recipient_account_name'],
                    'swift_code' => $data['swift_code'] ?? null,
                    'bank_address' => $data['recipient_bank_address'] ?? null,
                ],
            ]);

            return true;
        });
    }
}

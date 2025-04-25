<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Services\TransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class TransferController extends Controller
{
    protected $transferService;

    public function __construct(TransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    public function showTransferForm()
    {
        $user = Auth::user();
        $accounts = $user->accounts;
        
        return view('transfer.form', compact('accounts'));
    }

    public function handleTransfer(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'transfer_type' => 'required|in:self,other',
            'sender_account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:1',
            'pin' => 'required|digits:4', // Assuming a 4-digit PIN
        ]);

        // Validate recipient data depending on transfer type
        if ($request->transfer_type == 'self') {
            $request->validate([
                'recipient_account_id' => 'required|exists:accounts,id',
            ]);
        } else {
            $request->validate([
                'recipient_account_number' => 'required|exists:accounts,account_number',
            ]);
        }

        // Find sender account and user
        $senderAccount = Account::findOrFail($request->sender_account_id);
        $user = Auth::user();

        // Verify the user's pin
        if (!Hash::check($request->pin, $user->pin)) {
            return back()->with('error', 'Incorrect PIN');
        }

        //dd($request->all());

        // Handle transfer logic
        try {
            if ($request->transfer_type == 'self') {
                $recipientAccount = Account::findOrFail($request->recipient_account_id);

                // Ensure recipient is the same user (self-transfer check)
                if ($recipientAccount->user_id !== $user->id) {
                    return back()->with('error', 'You can only transfer between your own accounts.');
                }

                // Call the transfer service for self-transfer
                $this->transferService->transferBetweenOwnAccounts($senderAccount, $recipientAccount, $request->amount);

            } elseif ($request->transfer_type == 'other') {
                $recipientAccount = Account::where('account_number', $request->recipient_account_number)->firstOrFail();

                // Ensure recipient is a different user (external transfer check)
                if ($recipientAccount->user_id === $user->id) {
                    return back()->with('error', 'Use the self-transfer option to transfer between your own accounts.');
                }

                // Call the transfer service for other-user transfer
                $this->transferService->transferToOtherUsers($senderAccount, $recipientAccount, $request->amount);
            }

            return back()->with('success', 'Transfer successful.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    

}

<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Services\TransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $validated = $request->validate([
            'transfer_type' => 'required|in:self,other',
            'sender_account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:1',
            'pin' => 'required|digits:4', // Assuming a 4-digit PIN
        ]);

        $senderAccount = Account::findOrFail($request->sender_account_id);
        $user = Auth::user();

        // Verify the user's pin
        if ($user->pin !== $request->pin) {
            return back()->with('error', 'Incorrect PIN.');
        }

        // Handle the transfer
        try {
            if ($request->transfer_type == 'self') {
                $recipientAccount = Account::findOrFail($request->recipient_account_id);
                $this->transferService->transferFunds($senderAccount, $recipientAccount, $request->amount);
            } elseif ($request->transfer_type == 'other') {
                $recipientAccount = Account::where('account_number', $request->recipient_account_number)->first();
                if (!$recipientAccount) {
                    return back()->with('error', 'Recipient account not found.');
                }
                $this->transferService->transferFunds($senderAccount, $recipientAccount, $request->amount);
            }

            return back()->with('success', 'Transfer successful.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}

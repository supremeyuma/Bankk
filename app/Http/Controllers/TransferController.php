<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Services\TransferService;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    protected $transferService;

    public function __construct(TransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    /**
     * Show the transfer form.
     */
    public function showForm()
    {
        return view('transfer.form');  // Create this view to display the form
    }

    /**
     * Handle the internal transfer.
     */
    public function handleTransfer(Request $request)
    {
        // Validate form input
        $request->validate([
            'sender_account_id' => 'required|exists:accounts,id',
            'recipient_account_id' => 'required|exists:accounts,id|different:sender_account_id',
            'amount' => 'required|numeric|min:1',
        ]);

        $senderAccount = Account::find($request->sender_account_id);
        $recipientAccount = Account::find($request->recipient_account_id);
        $amount = $request->amount;

        try {
            // Perform the transfer
            $this->transferService->transferFunds($senderAccount, $recipientAccount, $amount);

            // Redirect or return success
            return redirect()->route('transfer.form')->with('success', 'Transfer successful!');
        } catch (\Exception $e) {
            // Return error message
            return redirect()->route('transfer.form')->with('error', $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\WireTransferService;
use Illuminate\Http\Request;

class WireTransferController extends Controller
{
    protected $wireTransferService;

    public function __construct(WireTransferService $wireTransferService)
    {
        $this->wireTransferService = $wireTransferService;
    }

    public function create()
    {
        return view('user.transfer.wire');
    }

    public function store(Request $request)
    {
        $request->validate([
            'transfer_type' => 'required|in:domestic,international',
            'sender_account_id' => 'required|exists:accounts,id',
            'recipient_bank_name' => 'required|string|max:255',
            'recipient_bank_country' => 'required|string|max:255',
            'recipient_account_number' => 'required|string|max:255',
            'recipient_account_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'pin' => 'required|string|max:4',
        ]);

        try {
            $this->wireTransferService->wireTransfer($request->all());
            return redirect()->back()->with('success', 'Wire transfer successful.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}

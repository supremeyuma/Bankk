<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function transactions()
    {
        $user = auth()->user();

        // Get all transactions for user's accounts
        $transactions = Transaction::whereIn('account_id', $user->accounts->pluck('id'))
            ->latest()
            ->paginate(10);

        return view('user.transactions', compact('transactions'));
    }

}

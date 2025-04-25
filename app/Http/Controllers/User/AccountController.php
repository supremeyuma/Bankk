<?php 

namespace App\Http\Controllers\User;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function getAccountByNumber($accountNumber)
    {
        $account = Account::where('account_number', $accountNumber)->with('user')->first();

        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        return response()->json([
            'name' => $account->user->name,
            'account_id' => $account->id,
        ]);
    }
}

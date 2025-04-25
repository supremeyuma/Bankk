<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function showPinForm()
{
    return view('users.set_pin');
}

public function storePin(Request $request)
{
    $request->validate([
        'pin' => 'required|digits:4|confirmed', // pin_confirmation field required
    ]);

    $user = auth()->user();
    $user->pin = bcrypt($request->pin);
    $user->save();

    return redirect()->back()->with('success', 'PIN set successfully.');
}

}

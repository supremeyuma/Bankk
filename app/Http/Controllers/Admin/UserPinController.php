<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserPinController extends Controller
{
    public function showForm()
    {
        return view('admin.set_pin');
    }

    public function setPin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'pin' => 'required|string|min:4|max:6',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();
        $user->pin = Hash::make($request->pin);
        $user->save();

        return redirect()->back()->with('success', 'PIN set successfully for ' . $user->email);
    }
}

@extends('layouts.app')

@section('content')
<h2>Set or Reset User PIN</h2>

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

<form action="{{ route('admin.setPin') }}" method="POST">
    @csrf
    <div>
        <label for="email">User Email</label>
        <input type="email" name="email" id="email" required>
    </div>

    <div>
        <label for="pin">New PIN</label>
        <input type="password" name="pin" id="pin" required minlength="4" maxlength="6">
    </div>

    <button type="submit">Set/Reset PIN</button>
</form>
@endsection

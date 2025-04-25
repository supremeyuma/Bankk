@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

<form action="{{ url('/set-pin') }}" method="POST">
    @csrf

    <div>
        <label for="pin">New PIN</label>
        <input type="password" name="pin" id="pin" required>
    </div>

    <div>
        <label for="pin_confirmation">Confirm PIN</label>
        <input type="password" name="pin_confirmation" id="pin_confirmation" required>
    </div>

    <button type="submit">Set PIN</button>
</form>
<!-- Add more form elements as needed -->
 
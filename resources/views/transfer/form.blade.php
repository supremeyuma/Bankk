<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Funds</title>
</head>
<body>

<h2>Internal Transfer</h2>

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@elseif(session('error'))
    <p style="color: red;">{{ session('error') }}</p>
@endif

<form action="{{ route('transfer.handle') }}" method="POST">
    @csrf
    <div>
    <label for="sender_account_id">Sender Account</label>

    <select name="sender_account_id" id="sender_account_id">
        @if($accounts->isEmpty())
            <option value="">No accounts available</option>
        @else
            @foreach($accounts as $account)
                <option value="{{ $account->id }}">{{ $account->account_number }} (Balance: ${{ $account->balance }})</option>
            @endforeach
        @endif
    </select>
    </div>

    <div>
        <label for="recipient_account_id">Recipient Account</label>
        <select name="recipient_account_id" id="recipient_account_id">
            @foreach($accounts as $account)
                <option value="{{ $account->id }}">{{ $account->account_number }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="amount">Amount</label>
        <input type="text" name="amount" id="amount" required>
    </div>

    <button type="submit">Transfer</button>
</form>

</body>
</html>

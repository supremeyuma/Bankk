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
        <<select name="sender_account_id" id="sender_account_id">
            @foreach($accounts as $account)
                <option value="{{ $account->id }}">{{ $account->account_number }} (Balance: ${{ $account->balance }})</option>
            @endforeach
        </select>

    </div>

    <div>
        <label for="recipient_account_id">Recipient Account</label>
        <select name="recipient_account_id" id="recipient_account_id">
            <!-- Will be populated with JavaScript -->
        </select>
    </div>

    <div>
        <label for="amount">Amount</label>
        <input type="text" name="amount" id="amount" required>
    </div>

    <button type="submit">Transfer</button>
</form>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const allAccounts = @json($accounts);
        const senderSelect = document.getElementById('sender_account_id');
        const recipientSelect = document.getElementById('recipient_account_id');

        function updateRecipientOptions() {
            const selectedSenderId = senderSelect.value;

            // Clear recipient options
            recipientSelect.innerHTML = '';

            // Show only accounts not equal to the sender account
            const filtered = allAccounts.filter(account => account.id != selectedSenderId);

            if (filtered.length === 0) {
                const option = document.createElement('option');
                option.value = '';
                option.text = 'You have only one account.';
                recipientSelect.appendChild(option);
            }

            filtered.forEach(account => {
                const option = document.createElement('option');
                option.value = account.id;
                option.text = `${account.account_number} (Balance: $${account.balance})`;
                recipientSelect.appendChild(option);
            });
        }

        senderSelect.addEventListener('change', updateRecipientOptions);
        updateRecipientOptions(); // run on page load
    });
</script>


</body>
</html>

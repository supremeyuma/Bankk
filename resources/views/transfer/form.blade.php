@extends('layouts.app')

@section('title', 'Internal Transfer') <!-- Page title -->

@section('content')

<h2>Internal Transfer</h2>

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@elseif(session('error'))
    <p style="color: red;">{{ session('error') }}</p>
@endif

<form action="{{ route('transfer.handle') }}" method="POST">
    @csrf

    <div>
        <label for="transfer_type">Transfer Type</label>
        <select name="transfer_type" id="transfer_type">
            <option value="self">Between My Accounts</option>
            <option value="other">To Another User</option>
        </select>
    </div>

    <div>
        <label for="sender_account_id">Sender Account</label>
        <select name="sender_account_id" id="sender_account_id">
            @foreach($accounts as $account)
                <option value="{{ $account->id }}">{{ $account->account_number }} (Balance: ${{ $account->balance }})</option>
            @endforeach
        </select>
    </div>

    {{-- Self-transfer --}}
    <div id="self_transfer_fields" class="hidden">
        <label for="recipient_account_id">Recipient Account</label>
        <select name="recipient_account_id" id="recipient_account_id">
            <!-- Will be populated by JavaScript -->
        </select>
    </div>

    {{-- Other-user transfer --}}
    <div id="other_transfer_fields" class="hidden other-fields">
        <label for="recipient_account_number">Recipient Account Number</label>
        <input type="text" name="recipient_account_number" id="recipient_account_number">

        <button type="button" onclick="verifyAccount()">Verify</button>
        <p id="account_name_display" style="margin-top: 5px;"></p>
    </div>


    <div>
        <label for="amount">Amount</label>
        <input type="text" name="amount" id="amount" required>
    </div>

    <div>
        <label for="pin">PIN</label>
        <input type="password" name="pin" id="pin" required>
    </div>

    <button type="submit">Transfer</button>
</form>


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const allAccounts = @json($accounts);
        const senderSelect = document.getElementById('sender_account_id');
        const recipientSelect = document.getElementById('recipient_account_id');
        const transferTypeSelect = document.getElementById('transfer_type');
        const selfFields = document.getElementById('self_transfer_fields');
        const otherFields = document.getElementById('other_transfer_fields');
        const recipientNumberInput = document.getElementById('recipient_account_number');
        const nameDisplay = document.getElementById('account_name_display');

        function updateRecipientOptions() {
            const selectedSenderId = senderSelect.value;
            recipientSelect.innerHTML = '';

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

        function toggleTransferType() {
            const type = transferTypeSelect.value;

            if (type === 'self') {
                selfFields.classList.remove('hidden');
                otherFields.classList.add('hidden');
                updateRecipientOptions();
            } else {
                selfFields.classList.add('hidden');
                otherFields.classList.remove('hidden');
                recipientNumberInput.value = '';
                nameDisplay.textContent = '';
            }
        }

        window.verifyAccount = async function () {
            const number = recipientNumberInput.value.trim();
            nameDisplay.textContent = '';

            if (!number) return;

            try {
                const response = await fetch(`/accounts/lookup/${number}`);
                const data = await response.json();

                if (data && data.name) {
                    nameDisplay.textContent = `Account Name: ${data.name}`;
                } else {
                    nameDisplay.textContent = `Account not found`;
                }
            } catch (e) {
                nameDisplay.textContent = `Error checking account`;
            }
        };

        recipientNumberInput.addEventListener('blur', window.verifyAccount);
        transferTypeSelect.addEventListener('change', toggleTransferType);
        senderSelect.addEventListener('change', updateRecipientOptions);

        // Fire once when the page loads
        toggleTransferType();
    });

    console.log('Transfer script loaded')
</script>
@endpush

@push('styles')
<style>
    .hidden {
        display: none !important;
    }
</style>
@endpush

@endsection

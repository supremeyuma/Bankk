@extends('layouts.app')

@section('title', 'Wire Transfer') <!-- Page title -->

@section('content')

<h2>Wire Transfer</h2>

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@elseif(session('error'))
    <p style="color: red;">{{ session('error') }}</p>
@endif


<form action="{{ route('user.wire.transfer.store') }}" method="POST">
    @csrf

    <!-- Transfer Type -->
    <div class="form-group mb-4">
        <label for="transfer_type">Transfer Type</label>
        <select id="transfer_type" name="transfer_type" class="form-control" onchange="toggleInternationalFields()" required>
            <option value="">Select Type</option>
            <option value="domestic">Domestic</option>
            <option value="international">International</option>
        </select>
    </div>

    <!-- Sender Account -->
    <div class="form-group mb-4">
        <label for="sender_account_id">Sender Account</label>
        <select id="sender_account_id" name="sender_account_id" class="form-control" required>
            @foreach(auth()->user()->accounts as $account)
                <option value="{{ $account->id }}">{{ $account->account_number }} - ${{ number_format($account->balance, 2) }}</option>
            @endforeach
        </select>
    </div>

    <!-- Recipient Bank Details -->
    <div class="form-group mb-4">
        <label for="recipient_bank_name">Recipient Bank Name</label>
        <input type="text" id="recipient_bank_name" name="recipient_bank_name" class="form-control" required>
    </div>

    <div class="form-group mb-4">
        <label for="recipient_bank_country">Recipient Bank Country</label>
        <input type="text" id="recipient_bank_country" name="recipient_bank_country" class="form-control" required>
    </div>

    <div class="form-group mb-4">
        <label for="recipient_account_number">Recipient Account Number</label>
        <input type="text" id="recipient_account_number" name="recipient_account_number" class="form-control" required>
    </div>

    <div class="form-group mb-4">
        <label for="recipient_account_name">Recipient Account Name</label>
        <input type="text" id="recipient_account_name" name="recipient_account_name" class="form-control" required>
    </div>

    <!-- SWIFT Code (International only) -->
    <div class="form-group mb-4" id="swift_code_field" style="display: none;">
        <label for="swift_code">SWIFT/BIC Code</label>
        <input type="text" id="swift_code" name="swift_code" class="form-control">
    </div>

    <!-- Recipient Bank Address (International only) -->
    <div class="form-group mb-4" id="recipient_bank_address_field" style="display: none;">
        <label for="recipient_bank_address">Recipient Bank Address</label>
        <input type="text" id="recipient_bank_address" name="recipient_bank_address" class="form-control">
    </div>

    <!-- Amount -->
    <div class="form-group mb-4">
        <label for="amount">Amount</label>
        <input type="number" id="amount" name="amount" class="form-control" required min="1" step="0.01">
    </div>

    <!-- Pin -->
    <div class="form-group mb-4">
        <label for="pin">Transaction PIN</label>
        <input type="password" id="pin" name="pin" class="form-control" required maxlength="4">
    </div>

    <button type="submit" class="btn btn-primary">Submit Transfer</button>
</form>

@push('scripts')
<script>
function toggleInternationalFields() {
    var transferType = document.getElementById('transfer_type').value;
    var swiftField = document.getElementById('swift_code_field');
    var addressField = document.getElementById('recipient_bank_address_field');
    
    if (transferType === 'international') {
        swiftField.style.display = 'block';
        addressField.style.display = 'block';
    } else {
        swiftField.style.display = 'none';
        addressField.style.display = 'none';
    }
}
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
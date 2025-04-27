@extends('layouts.app')

@section('content')
    <h2>My Transactions</h2>

    <table border="1" cellpadding="8" width="100%">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Flow</th>
                <th>Amount</th>
                <th>Currency</th>
                <th>From</th>
                <th>To</th>
                <th>Description</th>
                <th>Recipient Details</th> <!-- NEW column -->
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $tx)
                <tr>
                    <td>{{ $tx->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $tx->type)) }}</td>
                    <td>{{ ucfirst($tx->flow) ?? '-' }}</td>
                    <td>${{ number_format($tx->amount, 2) }}</td>
                    <td>{{ $tx->currency }}</td>
                    <td>{{ $tx->sender_account_id ?? '-' }}</td>
                    <td>{{ $tx->recipientAccount?->account_number ?? '-' }}</td>
                    <td>{{ $tx->description }}</td>

                    <td>
                        @if ($tx->recipient_details)
                            <ul style="padding-left: 15px;">
                                <li><strong>Bank Name:</strong> {{ $tx->recipient_details['bank_name'] ?? '-' }}</li>
                                <li><strong>Country:</strong> {{ $tx->recipient_details['bank_country'] ?? '-' }}</li>
                                <li><strong>Account No.:</strong> {{ $tx->recipient_details['account_number'] ?? '-' }}</li>
                                <li><strong>Account Name:</strong> {{ $tx->recipient_details['account_name'] ?? '-' }}</li>
                                <!--@if (isset($tx->recipient_details['swift_code']))
                                    <li><strong>SWIFT:</strong> {{ $tx->recipient_details['swift_code'] }}</li>
                                @endif
                                @if (isset($tx->recipient_details['bank_address']))
                                    <li><strong>Bank Address:</strong> {{ $tx->recipient_details['bank_address'] }}</li>
                                @endif-->
                            </ul>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
@endsection

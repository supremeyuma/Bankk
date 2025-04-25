@extends('layouts.app')

@section('content')
    <h2>My Transactions</h2>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Currency</th>
                <th>From</th>
                <th>To</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $tx)
                <tr>
                    <td>{{ $tx->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ ucfirst($tx->type) }}</td>
                    <td>${{ number_format($tx->amount, 2) }}</td>
                    <td>{{ $tx->currency }}</td>
                    <td>{{ $tx->sender_account_id ?? '-' }}</td>
                    <td>{{ $tx->recipient_account_id ?? '-' }}</td>
                    <td>{{ $tx->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $transactions->links() }}
@endsection

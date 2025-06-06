<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public const TYPES = [
        'deposit',
        'withdrawal',
        'internal_transfer',
        'domestic_wire',
        'international_wire',
    ];

    protected $fillable = [
        'account_id',
        'sender_account_id',
        'recipient_account_id',
        'amount',
        'type',
        'flow',
        'currency',
        'description',
        'recipient_details',

    ];

    protected $casts = [
        'recipient_details' => 'array',
    ];
    
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    
    public function recipientAccount()
    {
        return $this->belongsTo(Account::class, 'recipient_account_id');
    }
    
}

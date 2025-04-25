<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_account_id',
        'recipient_account_id',
        'amount',
        'type',
        'flow',
        'currency',
        'description',
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

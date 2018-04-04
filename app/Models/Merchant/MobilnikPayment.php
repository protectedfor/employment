<?php

namespace App\Models\Merchant;

use App\User;
use Illuminate\Database\Eloquent\Model;

class MobilnikPayment extends Model
{
    protected $fillable = [
        'query_id',
        'amount',
        'text',
        'billing_account',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

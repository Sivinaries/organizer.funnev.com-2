<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BalanceEntry extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'reference_type',
        'reference_id',
        'description',
    ];

    protected $casts = [
        'amount' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

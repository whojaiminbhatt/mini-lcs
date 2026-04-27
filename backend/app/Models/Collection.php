<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;
    protected $fillable = [
        'loan_id', 'amount_paid', 'payment_mode', 'location', 'collected_at', 'collected_by'
    ];

    protected $casts = [
        'collected_at' => 'datetime',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }
}

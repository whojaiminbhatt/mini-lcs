<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
    protected $fillable = [
        'loan_no', 'customer_name', 'mobile', 'address', 'total_amount', 'emi_amount'
    ];

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }
}

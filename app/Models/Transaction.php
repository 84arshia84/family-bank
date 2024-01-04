<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "Price",
        "gateway_result",
        "loan_id",
        "installment_id",
    ];
    protected $casts = [
        'gateway_result' => 'json'
    ];

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}

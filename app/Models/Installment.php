<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Installment extends Model
{
    use HasFactory;
    protected $fillable=
        [
            "Price",
            "date_of_payment",
            "Payment_status",
            "cost",
            "loan_id",
        ];
public function loan():BelongsTo
{
    return $this->belongsTo(Loan::class);
}
}


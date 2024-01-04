<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Installment extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable =
        [
            "date_of_payment",
            "Payment_status",
            "cost",
            "loan_id",
        ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

}


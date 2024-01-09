<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Transaction extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        "user_id",
        "Price",
        "gateway_result",
        "loan_id",
        "installment_id",
        "type",
        "date", // تاریخ
        "tracking_code", // کد پیگیری
        "description", // توضیحات
        "status"
    ];
    protected $casts = [
        'gateway_result' => 'object'
    ];

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

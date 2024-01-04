<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Loan extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    // استفاده از تریت حذف نرم
    protected $fillable = [
        'title_of_loan',
        'amount',
        'description',
        'status',
        'date_of_loan',
        'user_id',
    ];
    protected $casts = [
        'date_of_loan' => 'datetime'
    ];


//    public function installments(): HasMany
//    {
//        return $this->hasMany(Installment::class);
//    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }
}

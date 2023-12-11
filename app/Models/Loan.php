<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Loan extends Model
{
    use HasFactory, Notifiable, SoftDeletes; // استفاده از تریت حذف نرم
    protected $fillable =[
        'description',
        'amount',
        'title_of_loan',
        'user_id',
    ];



    public function installments ():HasMany
    {
        return $this->hasMany(Installment::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}

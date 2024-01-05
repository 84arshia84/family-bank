<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        "sheba_number",
        "kart_number",
        "bank_account_number"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


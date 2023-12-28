<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Wallet extends Model
{
    use HasFactory;
    protected $fillable=
        [
            'amount'
        ];

    public function user ():HasOne
    {
        return $this->hasOne(User::class);
    }

}

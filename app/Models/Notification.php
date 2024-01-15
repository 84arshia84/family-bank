<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'text',
        'user_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function setUrl($id): static
    {
        $this->url = route('pay', $id);
        return $this;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

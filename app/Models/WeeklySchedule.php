<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklySchedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'short_description',
        'more_details',
        'date'
    ];






    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

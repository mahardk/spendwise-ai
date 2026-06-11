<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'user_id', 'name', 'icon',
        'target_amount', 'saved_amount', 'deadline'
    ];

    protected $casts = [
        'deadline'      => 'date',
        'target_amount' => 'decimal:2',
        'saved_amount'  => 'decimal:2',
    ];

    public function getProgressPercentAttribute(): int
    {
        if ($this->target_amount <= 0) return 0;
        return min(100, (int)(($this->saved_amount / $this->target_amount) * 100));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackerGizi extends Model
{
    protected $table = 'tracker_gizi';

    protected $fillable = ['user_id', 'data', 'is_locked', 'submitted_at'];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'is_locked' => 'boolean',
            'submitted_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

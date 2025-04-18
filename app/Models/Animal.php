<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Animal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'species',
        'is_predator',
        'born_at',
        'image_path',
        'enclosure_id'
    ];

    protected $casts = [
        'born_at' => 'datetime',
        'is_predator' => 'boolean',
    ];

    // 1:N relationship with enclosure
    public function enclosure(): BelongsTo
    {
        return $this->belongsTo(Enclosure::class);
    }
}

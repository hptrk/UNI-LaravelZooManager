<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enclosure extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // name of the enclosure
        'limit', // maximum number of animals
        'feeding_at' // time of day for feeding
    ];

    // protected $casts = [
    //     'feeding_at' => 'datetime'
    // ];

    // accessor - only returns time in H:i format
    public function getFeedingAtAttribute($value)
    {
        if (!$value) return null;
        return substr($value, 0, 5);
    }

    // N:M relationship with users
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    // 1:N relationship with animals
    public function animals(): HasMany
    {
        return $this->hasMany(Animal::class);
    }
}

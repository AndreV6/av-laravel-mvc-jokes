<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Joke extends Model
{
    use HasFactory;

    protected $fillable = [
        'joke',
        'author_id'
    ];

    /**
     * Get the user who authored the joke
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}

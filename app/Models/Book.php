<?php

namespace App\Models;

use App\Traits\DateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{

    use DateTrait, HasFactory;

    protected $fillable = [
        'title',
        'author_id',
        'year',
        'isbn',
        'is_available'
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * Scope a query to only include Available
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }
}

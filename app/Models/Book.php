<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'author',
        'description',
        'thumbnail',
        'rating',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilterByAuthor($query, $author)
    {
        return $query->when($author, function ($q) use ($author) {
            $q->where('author', 'ILIKE', '%' . $author . '%');
        });
    }

    public function scopeFilterByRating($query, $rating)
    {
        return $query->when($rating, function ($q) use ($rating) {
            $q->where('rating', $rating);
        });
    }

    public function scopeFilterByDateRange($query, $start_date, $end_date)
    {
        return $query->when($start_date && $end_date, function ($q) use ($start_date, $end_date) {
            $q->whereBetween('created_at', [$start_date, $end_date]);
        });
    }
}
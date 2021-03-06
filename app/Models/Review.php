<?php

namespace App\Models;

use App\Traits\RecordActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory, RecordActivity;

    protected $fillable = [
        'product_id',
        'content',
        'rating',
    ];

    protected $with = ['product'];

    public function scopeWithSearch($query, $search)
    {
        return $query->when($search, function($query) use($search) {
            $query->whereHas('product', function($query) use($search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            });
        });
    }

    public function scopeWithSort($query, $sort)
    {
        return $query->when($sort, function($query) use($sort) {
            switch ($sort) {
                case 'rating.desc':
                    $query->orderBy('rating', 'desc');
                    break;
                case 'rating.asc':
                    $query->orderBy('rating', 'asc');
                    break;
                case 'date.desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'date.asc':
                    $query->orderBy('created_at', 'asc');
                    break;
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

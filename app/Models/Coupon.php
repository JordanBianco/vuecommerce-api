<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount',
    ];

    public function scopeWithSearch($query, $search)
    {
        return $query->when($search, function($query) use($search) {
            $query->where('code', 'LIKE', '%' . $search . '%');
        });
    }
}

<?php

namespace App\Models;

use App\Traits\RecordActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, RecordActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'order_number',
        'user_id',
        'total',
        'first_name',
        'last_name',
        'email',
        'country',
        'city',
        'province',
        'address',
        'zipcode',
        'phone',
        'notes',
        'archived_at',
    ];

    public function scopeWithSort($query, $sort)
    {
        $query->when($sort, function($query) use($sort) {
            switch ($sort) {
                case 'created_at.desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'created_at.asc':
                    $query->orderBy('created_at', 'asc');
                    break;
            }
        });
    }

    public function scopeWithFilterStatus($query, $fstatus)
    {
        return $query->when($fstatus, function($query) use($fstatus) {
            $query->where('status', $fstatus);
        });
    }

    public function scopeWithSearch($query, $search)
    {
        return $query->when($search, function($query) use($search) {
            $query->where('order_number', 'LIKE', '%' . $search . '%');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_order')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}

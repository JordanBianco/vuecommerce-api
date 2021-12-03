<?php

namespace App\Models;

use App\Traits\RecordActivity;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, RecordActivity;

    public static function boot()
    {
        parent::boot();

        static::created(function($user) {
            $user->cart()->create();
        });

        static::deleted(function($user) {
            $user->activities()->delete();
        });
    } 

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'country',
        'city',
        'province',
        'address',
        'zipcode',
        'phone',
        'password',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean'
    ];

    public function scopeWithSearch($query, $search)
    {
        return $query->when($search, function($query) use($search) {
            $query
                ->where('first_name', 'LIKE', '%' . $search . '%')
                ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                ->orWhere('email', 'LIKE', '%' . $search . '%');
        });
    }

    public function scopeWithEmailVerified($query, $email_verified)
    {
        return $query->when($email_verified, function($query) use($email_verified) {
            switch ($email_verified) {
                case 'true':
                    return $query->whereNotNull('email_verified_at');
                case 'false':
                    return $query->whereNull('email_verified_at');
            }
        });
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_user')->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}

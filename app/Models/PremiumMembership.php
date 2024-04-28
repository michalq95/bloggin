<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PremiumMembership extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = ["user_id", 'active', 'expiration_date'];

    protected static function booted()
    {
        static::created((function () {
            Cache::forget("donations");
        }));

        static::updated((function () {
            Cache::forget("donations");
        }));

        static::deleted((function () {
            Cache::forget("donations");
        }));

        parent::boot();
    }

    public function scopeFilter($query, array $filters)
    {

        if ($filters['user'] ?? false) {
            $query->where(function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->where('id', request('user'))
                        ->orWhere('name', 'like', '%' . request('user') . '%')
                        ->orWhere('email', 'like', '%' . request('user') . '%');
                });
            });
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}

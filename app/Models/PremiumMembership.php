<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PremiumMembership extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", 'active', 'expiration_date'];

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

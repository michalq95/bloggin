<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PremiumMembership extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", 'active', 'expiration_date'];
}

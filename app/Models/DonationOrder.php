<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationOrder extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'price', 'status', 'piid', 'donation_id'];
}

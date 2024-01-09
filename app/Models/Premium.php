<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Premium extends Model
{
    use HasFactory;

    public function credit_cards()
    {
        return $this->hasMany(Credit_card::class);
    }

    public function paypals()
    {
        return $this->hasMany(Paypal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

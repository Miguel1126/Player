<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public function credit_card()
    {
        return $this->belongsTo(Credit_card::class);
    }

    public function paypal()
    {
        return $this->belongsTo(Paypal::class);
    }
}

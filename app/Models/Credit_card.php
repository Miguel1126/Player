<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit_card extends Model
{
    use HasFactory;
    
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}

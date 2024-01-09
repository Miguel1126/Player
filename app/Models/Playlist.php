<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Playlist extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = ['title', 'state', 'deleted_at', 'user_id']; // Incluye 'state' en la lista de atributos fillable
    protected $dates = ['deleted_at']; // Utiliza un campo de fecha para el borrado suave

    // Definir el valor predeterminado para 'state'
    protected $attributes = [
        'state' => 'A', // Establece 'A' como valor predeterminado para 'state'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function songs()
    {
        return $this->belongsToMany(Song::class);
    }
}

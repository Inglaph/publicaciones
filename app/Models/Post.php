<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['body'];

    // Relación con la tabla users
    // Un post pertenece a un usuario
    // Si se elimina un post, no se elimina el usuario
    public function user()
    {
        // belongsTo es una relación inversa de hasMany que está definida en el modelo User
        // belongsTo se usa para indicar que un post pertenece a un usuario
        return $this->belongsTo(User::class);
    }
}

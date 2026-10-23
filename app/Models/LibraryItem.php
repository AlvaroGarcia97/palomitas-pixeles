<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Una película guardada por un usuario en su club.
#[Fillable([
    'tmdb_id', 'title', 'poster_path', 'release_date', 'overview',
    'status', 'rating', 'review', 'watched_at',
])]
class LibraryItem extends Model
{
    // Conversiones de tipos al leer/escribir en la base de datos.
    protected function casts(): array
    {
        return [
            'watched_at' => 'datetime',
            'rating'     => 'integer',
        ];
    }

    // Relación inversa: cada película del club pertenece a un usuario.
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

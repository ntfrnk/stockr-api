<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovementType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    /* ============================================================
     |  RELACIONES
     ============================================================ */

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }


    /* ============================================================
     |  HELPERS AVANZADOS
     ============================================================ */

    /**
     * Verificar si el tipo de movimiento coincide con un slug.
     */
    public function is(string $slug): bool
    {
        return $this->slug === $slug;
    }

    /**
     * Saber si es una ENTRADA de stock.
     */
    public function isEntry(): bool
    {
        return in_array($this->slug, ['entry', 'entrada', 'in']);
    }

    /**
     * Saber si es una SALIDA de stock.
     */
    public function isExit(): bool
    {
        return in_array($this->slug, ['exit', 'salida', 'out']);
    }

    /**
     * Saber si es un AJUSTE de stock.
     */
    public function isAdjustment(): bool
    {
        return in_array($this->slug, ['adjustment', 'ajuste']);
    }

    /**
     * Nombre amigable del tipo (para reportes o API).
     */
    public function label(): string
    {
        return ucfirst($this->name);
    }

    /**
     * Información resumida para API.
     */
    public function summary()
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }
}

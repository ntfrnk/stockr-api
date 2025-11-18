<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    /* ============================================================
     |  RELACIONES
     ============================================================ */

    public function sales()
    {
        return $this->hasMany(Sale::class, 'sale_status_id');
    }


    /* ============================================================
     |  HELPERS AVANZADOS
     ============================================================ */

    /**
     * Verificar si el estado coincide con un slug.
     */
    public function is(string $slug): bool
    {
        return $this->slug === $slug;
    }

    /**
     * Saber si la venta está completada.
     */
    public function isCompleted(): bool
    {
        return $this->is('completed');
    }

    /**
     * Saber si la venta está cancelada.
     */
    public function isCancelled(): bool
    {
        return $this->is('cancelled');
    }

    /**
     * Saber si la venta está pendiente.
     */
    public function isPending(): bool
    {
        return $this->is('pending');
    }

    /**
     * Saber si el estado representa un flujo exitoso.
     */
    public function isSuccess(): bool
    {
        return $this->isCompleted();
    }

    /**
     * Saber si el estado representa un error.
     */
    public function isError(): bool
    {
        return $this->isCancelled();
    }

    /**
     * Nombre amigable.
     */
    public function label(): string
    {
        return ucfirst($this->name);
    }


    /* ============================================================
     |  HELPERS PARA API / PRESENTACIÓN
     ============================================================ */

    public function summary()
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }

    public function fullDetail()
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'slug'     => $this->slug,
            'sales'    => $this->sales()->count(),
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    /* ============================================================
     |  RELACIONES
     ============================================================ */

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'purchase_status_id');
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
     * Saber si la compra ya fue recibida / completada.
     */
    public function isCompleted(): bool
    {
        return $this->is('completed') || $this->is('received');
    }

    /**
     * Saber si la compra está cancelada.
     */
    public function isCancelled(): bool
    {
        return $this->is('cancelled');
    }

    /**
     * Saber si aún está pendiente.
     */
    public function isPending(): bool
    {
        return $this->is('pending');
    }

    /**
     * Saber si el estado indica flujo exitoso.
     */
    public function isSuccess(): bool
    {
        return $this->isCompleted();
    }

    /**
     * Saber si el estado representa un error o interrupción.
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
            'id'        => $this->id,
            'name'      => $this->name,
            'slug'      => $this->slug,
            'purchases' => $this->purchases()->count(),
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'phone',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    /* ============================================================
     |  RELACIONES
     ============================================================ */

    public function store()
    {
        return $this->belongsTo(Store::class);
    }


    /* ============================================================
     |  HELPERS AVANZADOS
     ============================================================ */

    /**
     * Saber si el contacto está activo.
     */
    public function isActive(): bool
    {
        return $this->status === 1;
    }

    /**
     * Activar este contacto.
     */
    public function activate(): void
    {
        $this->status = 1;
        $this->save();
    }

    /**
     * Desactivar este contacto.
     */
    public function deactivate(): void
    {
        $this->status = 0;
        $this->save();
    }

    /**
     * Formato de teléfono “limpio” (ideal para UI).
     */
    public function formattedPhone(): string
    {
        return preg_replace('/\s+/', ' ', trim($this->phone));
    }


    /* ============================================================
     |  HELPERS PARA API / PRESENTACIÓN
     ============================================================ */

    /**
     * Resumen ideal para listados o selects.
     */
    public function summary()
    {
        return [
            'id'        => $this->id,
            'phone'     => $this->formattedPhone(),
            'active'    => $this->isActive(),
        ];
    }

    /**
     * Detalle completo de contacto de tienda.
     */
    public function fullDetail()
    {
        return [
            'id'        => $this->id,
            'store_id'  => $this->store_id,
            'phone'     => $this->formattedPhone(),
            'status'    => $this->status,
            'store'     => $this->store,
        ];
    }
}

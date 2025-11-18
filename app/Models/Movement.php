<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'user_id',
        'movement_type_id',
        'date',
        'movementable_id',
        'movementable_type',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    /* ============================================================
     |  RELACIONES
     ============================================================ */

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(MovementType::class, 'movement_type_id');
    }

    // Relación polimórfica
    public function movementable()
    {
        return $this->morphTo();
    }


    /* ============================================================
     |  HELPERS AVANZADOS: TIPO DE MOVIMIENTO
     ============================================================ */

    /**
     * Saber si el movimiento es una entrada.
     * (asume slugs 'entry', 'entrada', etc.)
     */
    public function isEntry(): bool
    {
        $slug = $this->type->slug ?? null;
        return in_array($slug, ['entry', 'entrada', 'in']);
    }

    /**
     * Saber si el movimiento es una salida.
     */
    public function isExit(): bool
    {
        $slug = $this->type->slug ?? null;
        return in_array($slug, ['exit', 'salida', 'out']);
    }

    /**
     * Saber si es un ajuste (por ejemplo, corrección de stock).
     */
    public function isAdjustment(): bool
    {
        $slug = $this->type->slug ?? null;
        return in_array($slug, ['adjustment', 'ajuste']);
    }


    /* ============================================================
     |  HELPERS AVANZADOS: ORIGEN DEL MOVIMIENTO
     ============================================================ */

    /**
     * Obtener el tipo de origen en forma textual:
     * "Purchase", "Sale", "Adjustment", etc.
     */
    public function originType(): string
    {
        return class_basename($this->movementable_type ?? '');
    }

    /**
     * Obtener el ID del documento origen.
     */
    public function originId(): ?int
    {
        return $this->movementable_id;
    }

    /**
     * Descripción completa del origen del movimiento.
     */
    public function originDescription(): string
    {
        $origin = $this->movementable;

        if (!$origin) {
            return 'Sin origen';
        }

        // Para compras
        if ($origin instanceof \App\Models\Purchase) {
            return "Compra #{$origin->id}" . ($origin->number ? " ({$origin->number})" : "");
        }

        // Para ventas
        if ($origin instanceof \App\Models\Sale) {
            return "Venta #{$origin->id}" . ($origin->number ? " ({$origin->number})" : "");
        }

        return "Origen {$this->originType()} #{$this->originId()}";
    }


    /* ============================================================
     |  HELPERS PARA AUDITORÍA Y REPORTES
     ============================================================ */

    /**
     * Texto formateado del tipo de movimiento.
     */
    public function typeLabel(): string
    {
        return $this->type->name ?? 'Desconocido';
    }

    /**
     * Mini-resumen para API o dashboards.
     */
    public function summary()
    {
        return [
            'id'        => $this->id,
            'date'      => $this->date->format('Y-m-d H:i:s'),
            'store'     => $this->store->name ?? null,
            'user'      => $this->user->email ?? null,
            'type'      => $this->typeLabel(),
            'origin'    => $this->originDescription(),
        ];
    }

    /**
     * Detalle completo del movimiento.
     */
    public function fullDetail()
    {
        return [
            'id'            => $this->id,
            'date'          => $this->date,
            'store_id'      => $this->store_id,
            'user_id'       => $this->user_id,
            'movement_type' => $this->type,
            'origin_type'   => $this->originType(),
            'origin'        => $this->movementable,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}

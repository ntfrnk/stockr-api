<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'payment_method_id',
        'payment_entity_id',
        'amount',
        'reference',
        'payment_date',
        'notes',
        'completed',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'payment_date' => 'date',
        'completed'    => 'boolean',
    ];


    /* ============================================================
     |  RELACIONES
     ============================================================ */

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function entity()
    {
        return $this->belongsTo(PaymentEntity::class, 'payment_entity_id');
    }


    /* ============================================================
     |  HELPERS AVANZADOS: ESTADO DEL PAGO
     ============================================================ */

    /**
     * Saber si el pago está completado.
     */
    public function isCompleted(): bool
    {
        return $this->completed === true;
    }

    /**
     * Saber si está pendiente.
     */
    public function isPending(): bool
    {
        return !$this->completed;
    }

    /**
     * Marcar el pago como completado.
     */
    public function markCompleted(): void
    {
        $this->completed = true;
        $this->save();
    }

    /**
     * Marcar el pago como pendiente.
     */
    public function markPending(): void
    {
        $this->completed = false;
        $this->save();
    }


    /* ============================================================
     |  HELPERS AVANZADOS: MÉTODO Y ENTIDAD
     ============================================================ */

    /**
     * Nombre del método de pago (ej: "Transferencia", "Efectivo").
     */
    public function methodName(): string
    {
        return $this->method->name ?? 'Desconocido';
    }

    /**
     * Nombre de la entidad financiera (banco, billetera, etc.).
     */
    public function entityName(): string
    {
        return $this->entity->name ?? '-';
    }

    /**
     * Slug del método de pago.
     */
    public function methodSlug(): string
    {
        return $this->method->slug ?? '';
    }

    /**
     * Verificar si el método de pago es uno específico.
     */
    public function isMethod(string $slug): bool
    {
        return $this->methodSlug() === $slug;
    }


    /* ============================================================
     |  HELPERS PARA API / PRESENTACIÓN
     ============================================================ */

    /**
     * Resumen ideal para listados de pagos.
     */
    public function summary()
    {
        return [
            'id'          => $this->id,
            'amount'      => (float) $this->amount,
            'date'        => optional($this->payment_date)->format('Y-m-d'),
            'method'      => $this->methodName(),
            'entity'      => $this->entityName(),
            'reference'   => $this->reference,
            'completed'   => $this->completed,
        ];
    }

    /**
     * Detalle completo para dashboards o vistas avanzadas.
     */
    public function fullDetail()
    {
        return [
            'id'             => $this->id,
            'purchase'       => $this->purchase,
            'method'         => $this->method,
            'entity'         => $this->entity,
            'amount'         => (float) $this->amount,
            'payment_date'   => $this->payment_date,
            'reference'      => $this->reference,
            'completed'      => $this->completed,
            'notes'          => $this->notes,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}

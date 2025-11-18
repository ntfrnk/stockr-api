<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'user_id',
        'purchase_status_id',
        'number',
        'date',
        'notes',
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

    public function status()
    {
        return $this->belongsTo(PurchaseStatus::class, 'purchase_status_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function payments()
    {
        return $this->hasMany(PurchasePayment::class);
    }

    // Relación polimórfica con movimientos
    public function movements()
    {
        return $this->morphMany(Movement::class, 'movementable');
    }


    /* ============================================================
     |  HELPERS AVANZADOS: CALCULOS FINANCIEROS
     ============================================================ */

    /**
     * Subtotal = suma de (cantidad * costo unitario)
     */
    public function subtotal(): float
    {
        return (float) $this->items()
            ->selectRaw('SUM(quantity * cost_price) as subtotal')
            ->value('subtotal') ?? 0;
    }

    /**
     * Total = subtotal (si agregás impuestos o descuentos, se pueden sumar acá)
     */
    public function total(): float
    {
        return $this->subtotal();
    }

    /**
     * Total pagado.
     */
    public function totalPaid(): float
    {
        return (float) $this->payments()
            ->where('completed', true)
            ->sum('amount');
    }

    /**
     * Saldo pendiente.
     */
    public function balance(): float
    {
        return $this->total() - $this->totalPaid();
    }

    /**
     * Determina si la compra está completamente pagada.
     */
    public function isFullyPaid(): bool
    {
        return $this->balance() <= 0;
    }


    /* ============================================================
     |  HELPERS AVANZADOS: ITEMS Y PAGOS
     ============================================================ */

    /**
     * Saber si esta compra tiene ítems cargados.
     */
    public function hasItems(): bool
    {
        return $this->items()->exists();
    }

    /**
     * Saber si ya tiene pagos asociados.
     */
    public function hasPayments(): bool
    {
        return $this->payments()->exists();
    }


    /* ============================================================
     |  HELPERS AVANZADOS: ESTADO
     ============================================================ */

    /**
     * Nombre del estado.
     */
    public function statusName(): string
    {
        return $this->status->name ?? 'Desconocido';
    }

    /**
     * Saber si está en un estado específico (por slug).
     */
    public function isStatus(string $slug): bool
    {
        return optional($this->status)->slug === $slug;
    }

    /**
     * Ejemplo: saber si está marcada como "cancelada".
     */
    public function isCancelled(): bool
    {
        return $this->isStatus('cancelled');
    }

    /**
     * Ejemplo: saber si ya fue recibida/completada.
     */
    public function isCompleted(): bool
    {
        return $this->isStatus('completed');
    }


    /* ============================================================
     |  HELPERS PARA API / RESPUESTAS JSON
     ============================================================ */

    /**
     * Resumen para listar compras en API o dashboard.
     */
    public function summary()
    {
        return [
            'id'       => $this->id,
            'number'   => $this->number,
            'date'     => $this->date->format('Y-m-d H:i:s'),
            'total'    => $this->total(),
            'paid'     => $this->totalPaid(),
            'balance'  => $this->balance(),
            'status'   => $this->statusName(),
        ];
    }

    /**
     * Detalle completo para vista interna o API.
     */
    public function fullDetail()
    {
        return [
            'id'         => $this->id,
            'store_id'   => $this->store_id,
            'user_id'    => $this->user_id,
            'number'     => $this->number,
            'date'       => $this->date,
            'items'      => $this->items,
            'subtotal'   => $this->subtotal(),
            'total'      => $this->total(),
            'paid'       => $this->totalPaid(),
            'balance'    => $this->balance(),
            'payments'   => $this->payments,
            'status'     => $this->statusName(),
            'notes'      => $this->notes,
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'cost_price',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
    ];

    /* ============================================================
     |  RELACIONES
     ============================================================ */

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    /* ============================================================
     |  HELPERS AVANZADOS: CALCULOS POR ÍTEM
     ============================================================ */

    /**
     * Resultado de: cantidad * costo unitario
     */
    public function totalCost(): float
    {
        return (float) ($this->quantity * $this->cost_price);
    }

    /**
     * Saber cuánto stock suma este ítem.
     */
    public function stockImpact(): int
    {
        return (int) $this->quantity; // siempre suma stock
    }

    /**
     * Costo unitario como float limpio.
     */
    public function unitCost(): float
    {
        return (float) $this->cost_price;
    }


    /* ============================================================
     |  HELPERS PARA API / PRESENTACIÓN
     ============================================================ */

    /**
     * Resumen para listados de compras.
     */
    public function summary()
    {
        return [
            'product_id'    => $this->product_id,
            'product_name'  => optional($this->product)->name,
            'quantity'      => $this->quantity,
            'unit_cost'     => $this->unitCost(),
            'total_cost'    => $this->totalCost(),
        ];
    }

    /**
     * Detalle completo para dashboards internos o vista avanzada.
     */
    public function fullDetail()
    {
        return [
            'id'            => $this->id,
            'purchase_id'   => $this->purchase_id,
            'product'       => $this->product,
            'quantity'      => $this->quantity,
            'unit_cost'     => $this->unitCost(),
            'total_cost'    => $this->totalCost(),
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'sale_price',
        'discount',
    ];

    protected $casts = [
        'sale_price' => 'decimal:2',
        'discount'   => 'decimal:2',
    ];

    /* ============================================================
     |  RELACIONES
     ============================================================ */

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    /* ============================================================
     |  HELPERS AVANZADOS: CALCULOS POR ÍTEM
     ============================================================ */

    /**
     * Resultado de: cantidad * precio unitario
     */
    public function grossTotal(): float
    {
        return (float) ($this->quantity * $this->sale_price);
    }

    /**
     * Total del ítem luego de restar el descuento.
     */
    public function netTotal(): float
    {
        return max(0, $this->grossTotal() - $this->discount);
    }

    /**
     * Saber si este ítem tiene descuento aplicado.
     */
    public function hasDiscount(): bool
    {
        return $this->discount > 0;
    }

    /**
     * Valor del descuento en porcentaje.
     *
     * Ejemplo:
     * sale_price = 100
     * discount = 20
     * → discountPercent() = 20%
     */
    public function discountPercent(): float
    {
        if ($this->sale_price <= 0) {
            return 0;
        }

        return round(($this->discount / ($this->quantity * $this->sale_price)) * 100, 2);
    }

    /**
     * Saber cuánto stock afecta el ítem.
     */
    public function stockImpact(): int
    {
        return -(int) $this->quantity; // siempre resta stock
    }


    /* ============================================================
     |  HELPERS PARA API / PRESENTACIÓN
     ============================================================ */

    /**
     * Resumen para endpoints de ventas.
     */
    public function summary()
    {
        return [
            'product_id'    => $this->product_id,
            'product_name'  => optional($this->product)->name,
            'quantity'      => $this->quantity,
            'unit_price'    => (float) $this->sale_price,
            'discount'      => (float) $this->discount,
            'total'         => $this->netTotal(),
        ];
    }

    /**
     * Detalle completo para dashboards o paneles administrativos.
     */
    public function fullDetail()
    {
        return [
            'id'            => $this->id,
            'sale_id'       => $this->sale_id,
            'product'       => $this->product,
            'quantity'      => $this->quantity,
            'unit_price'    => (float) $this->sale_price,
            'gross_total'   => $this->grossTotal(),
            'discount'      => (float) $this->discount,
            'discount_pct'  => $this->discountPercent(),
            'net_total'     => $this->netTotal(),
        ];
    }
}

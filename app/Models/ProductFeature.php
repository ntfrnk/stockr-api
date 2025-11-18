<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'value',
    ];

    /* ============================================================
     |  RELACIONES
     ============================================================ */

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /* ============================================================
     |  HELPERS
     ============================================================ */

    /**
     * Nombre completo en formato "Nombre: Valor".
     */
    public function label(): string
    {
        if ($this->name && $this->value) {
            return "{$this->name}: {$this->value}";
        }

        return $this->name ?? 'Característica';
    }

    /**
     * Para listados en UI o API.
     */
    public function summary()
    {
        return [
            'name'  => $this->name,
            'value' => $this->value,
            'label' => $this->label(),
        ];
    }
}

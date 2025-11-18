<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    /* ============================================================
     |  RELACIONES
     ============================================================ */

    public function salePayments()
    {
        return $this->hasMany(SalePayment::class);
    }

    public function purchasePayments()
    {
        return $this->hasMany(PurchasePayment::class);
    }


    /* ============================================================
     |  HELPERS AVANZADOS
     ============================================================ */

    /**
     * Verificar si el método coincide con un slug.
     * Ej: $method->is('transfer');
     */
    public function is(string $slug): bool
    {
        return $this->slug === $slug;
    }

    /**
     * Saber si el método es "efectivo".
     */
    public function isCash(): bool
    {
        return $this->slug === 'cash';
    }

    /**
     * Saber si es transferencia bancaria.
     */
    public function isTransfer(): bool
    {
        return $this->slug === 'transfer';
    }

    /**
     * Saber si es tarjeta (débito o crédito).
     */
    public function isCard(): bool
    {
        return in_array($this->slug, [
            'credit_card',
            'debit_card',
            'card',
        ]);
    }

    /**
     * Saber si es billetera virtual.
     */
    public function isWallet(): bool
    {
        return in_array($this->slug, [
            'mercadopago',
            'mp',
            'wallet',
            'virtual_wallet',
        ]);
    }

    /**
     * Etiqueta amigable para mostrar en UI.
     */
    public function label(): string
    {
        return ucfirst($this->name);
    }

    /**
     * Para listados rápidos en API.
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

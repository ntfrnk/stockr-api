<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentEntity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',  // banco, wallet, tarjeta, otro
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
     |  HELPERS AVANZADOS: TIPO DE ENTIDAD
     ============================================================ */

    /**
     * Verificar si la entidad coincide con un slug.
     */
    public function is(string $slug): bool
    {
        return $this->slug === $slug;
    }

    /**
     * Saber si es un banco.
     */
    public function isBank(): bool
    {
        return $this->type === 'bank';
    }

    /**
     * Saber si es una billetera virtual.
     */
    public function isWallet(): bool
    {
        return $this->type === 'wallet';
    }

    /**
     * Saber si es una entidad de tarjeta (VISA, Mastercard, etc.).
     */
    public function isCardIssuer(): bool
    {
        return $this->type === 'card_issuer';
    }

    /**
     * Saber si es una entidad miscelánea (otro).
     */
    public function isOther(): bool
    {
        return $this->type === 'other';
    }


    /* ============================================================
     |  HELPERS PARA API / PRESENTACIÓN
     ============================================================ */

    /**
     * Nombre para UI.
     */
    public function label(): string
    {
        return ucfirst($this->name);
    }

    /**
     * Tipo en formato amigable.
     */
    public function typeLabel(): string
    {
        return match ($this->type) {
            'bank'        => 'Banco',
            'wallet'      => 'Billetera virtual',
            'card_issuer' => 'Emisor de tarjeta',
            default       => 'Otro',
        };
    }

    /**
     * Resumen ideal para listados o selects de frontend.
     */
    public function summary()
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'slug'  => $this->slug,
            'type'  => $this->type,
            'label' => $this->typeLabel(),
        ];
    }

    /**
     * Detalle completo de la entidad.
     */
    public function fullDetail()
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'slug'      => $this->slug,
            'type'      => $this->type,
            'type_text' => $this->typeLabel(),
            'sale_payments'     => $this->salePayments,
            'purchase_payments' => $this->purchasePayments,
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'name',
        'lastname',
        'email',
        'phone',
        'address',
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

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }


    /* ============================================================
     |  HELPERS AVANZADOS: CONTACTO
     ============================================================ */

    /**
     * Obtener el nombre completo del cliente.
     */
    public function displayName(): string
    {
        $fullName = trim($this->name . ' ' . ($this->lastname ?? ''));

        return $fullName !== '' ? $fullName : 'Cliente sin nombre';
    }

    /**
     * Saber si el cliente tiene email registrado.
     */
    public function hasEmail(): bool
    {
        return !empty($this->email);
    }

    /**
     * Saber si el cliente tiene teléfono registrado.
     */
    public function hasPhone(): bool
    {
        return !empty($this->phone);
    }


    /* ============================================================
     |  HELPERS AVANZADOS: ESTADISTICAS DE VENTAS
     ============================================================ */

    /**
     * Cuántas ventas tiene este cliente.
     */
    public function totalSalesCount(): int
    {
        return $this->sales()->count();
    }

    /**
     * Total de dinero gastado por el cliente.
     */
    public function totalSpent(): float
    {
        return (float) $this->sales()
            ->get()
            ->sum(function ($sale) {
                return $sale->total();
            });
    }

    /**
     * Última venta registrada.
     */
    public function lastSale()
    {
        return $this->sales()
            ->orderByDesc('date')
            ->first();
    }

    /**
     * Promedio por ticket (venta promedio).
     */
    public function averageTicket(): float
    {
        $count = $this->totalSalesCount();

        return $count > 0
            ? round($this->totalSpent() / $count, 2)
            : 0;
    }

    /**
     * Total que el cliente aún debe pagar.
     */
    public function pendingBalance(): float
    {
        return (float) $this->sales()
            ->get()
            ->sum(function ($sale) {
                return $sale->balance();
            });
    }

    /**
     * Saber si el cliente tiene deudas.
     */
    public function hasDebt(): bool
    {
        return $this->pendingBalance() > 0;
    }


    /* ============================================================
     |  HELPERS PARA API / PRESENTACIÓN
     ============================================================ */

    /**
     * Resumen para listados de clientes en API o dashboard.
     */
    public function summary()
    {
        return [
            'id'              => $this->id,
            'name'            => $this->displayName(),
            'email'           => $this->email,
            'phone'           => $this->phone,
            'total_spent'     => $this->totalSpent(),
            'pending_balance' => $this->pendingBalance(),
            'sales_count'     => $this->totalSalesCount(),
            'last_sale'       => optional($this->lastSale())->date?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Detalle completo del cliente para vista interna.
     */
    public function fullDetail()
    {
        return [
            'id'              => $this->id,
            'store_id'        => $this->store_id,
            'name'            => $this->displayName(),
            'email'           => $this->email,
            'phone'           => $this->phone,
            'address'         => $this->address,
            'status'          => $this->status,
            'sales'           => $this->sales()->get(),
            'total_spent'     => $this->totalSpent(),
            'pending_balance' => $this->pendingBalance(),
            'sales_count'     => $this->totalSalesCount(),
            'avg_ticket'      => $this->averageTicket(),
            'last_sale'       => $this->lastSale(),
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'street_name',
        'street_number',
        'country',
        'state',
        'city',
        'postal_code',
        'status',
    ];

    protected $casts = [
        'street_number' => 'integer',
        'country'       => 'integer',
        'state'         => 'integer',
        'city'          => 'integer',
        'postal_code'   => 'integer',
        'status'        => 'integer',
    ];

    /* ============================================================
     |  RELACIONES
     ============================================================ */

    public function store()
    {
        return $this->belongsTo(Store::class);
    }


    /* ============================================================
     |  HELPERS AVANZADOS: ESTADO
     ============================================================ */

    public function isActive(): bool
    {
        return $this->status === 1;
    }

    public function activate(): void
    {
        $this->status = 1;
        $this->save();
    }

    public function deactivate(): void
    {
        $this->status = 0;
        $this->save();
    }


    /* ============================================================
     |  HELPERS: DIRECCIÓN FORMATEADA
     ============================================================ */

    /**
     * Retorna una representación amigable de la dirección.
     */
    public function fullAddress(): string
    {
        $parts = [];

        if ($this->street_name) {
            $parts[] = $this->street_name;
        }

        if ($this->street_number) {
            $parts[] = $this->street_number;
        }

        if (!empty($this->postal_code)) {
            $parts[] = 'CP ' . $this->postal_code;
        }

        // Usamos IDs de country/state/city tal como están en la migración.
        // Si más adelante necesitás cargar nombres reales, podemos hacer un "resolver".
        $parts[] = "Country {$this->country}";
        $parts[] = "State {$this->state}";
        $parts[] = "City {$this->city}";

        return trim(implode(', ', $parts));
    }

    /**
     * Saber si tiene información mínima de dirección.
     */
    public function isComplete(): bool
    {
        return !empty($this->street_name)
            && !empty($this->street_number)
            && !empty($this->city);
    }


    /* ============================================================
     |  HELPERS PARA API / PRESENTACIÓN
     ============================================================ */

    public function summary()
    {
        return [
            'id'         => $this->id,
            'address'    => $this->fullAddress(),
            'active'     => $this->isActive(),
        ];
    }

    public function fullDetail()
    {
        return [
            'id'           => $this->id,
            'store_id'     => $this->store_id,
            'street_name'  => $this->street_name,
            'street_number'=> $this->street_number,
            'postal_code'  => $this->postal_code,
            'country'      => $this->country,
            'state'        => $this->state,
            'city'         => $this->city,
            'status'       => $this->status,
            'address'      => $this->fullAddress(),
            'store'        => $this->store,
        ];
    }
}

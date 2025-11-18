<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPicture extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'link',
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
     * Devuelve la URL de la imagen.
     * Si en el futuro usás almacenamiento local, acá se puede transformar.
     */
    public function url(): ?string
    {
        return $this->link;
    }

    /**
     * Devuelve un nombre amigable para UI si existe.
     */
    public function label(): string
    {
        return $this->name ?: 'Imagen';
    }

    /**
     * Para listados en el frontend.
     */
    public function summary()
    {
        return [
            'id'    => $this->id,
            'name'  => $this->label(),
            'url'   => $this->url(),
        ];
    }
}

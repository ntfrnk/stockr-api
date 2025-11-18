<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'store_id',
        'category_id',
        'barcode',
        'name',
        'description',
        'current_stock',
        'min_stock',
        'sale_price',
        'cost_price',
        'offer_price',
        'on_sale',
    ];

    protected $casts = [
        'on_sale'     => 'boolean',
        'sale_price'  => 'decimal:2',
        'cost_price'  => 'decimal:2',
        'offer_price' => 'decimal:2',
    ];


    /* ============================================================
     |  RELACIONES
     ============================================================ */

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function features()
    {
        return $this->hasMany(ProductFeature::class);
    }

    public function pictures()
    {
        return $this->hasMany(ProductPicture::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }


    /* ============================================================
     |  HELPERS AVANZADOS: PRECIO
     ============================================================ */

    /**
     * Obtener el precio final (si está en oferta devuelve offer_price).
     */
    public function finalPrice(): float
    {
        return $this->on_sale && $this->offer_price > 0
            ? (float) $this->offer_price
            : (float) $this->sale_price;
    }

    /**
     * Saber si está en oferta.
     */
    public function isOnSale(): bool
    {
        return $this->on_sale && $this->offer_price > 0;
    }


    /* ============================================================
     |  HELPERS AVANZADOS: STOCK
     ============================================================ */

    /**
     * Saber si el producto necesita reposición.
     */
    public function needsRestock(): bool
    {
        return $this->current_stock <= $this->min_stock;
    }

    /**
     * Saber si está SIN stock.
     */
    public function isOutOfStock(): bool
    {
        return $this->current_stock <= 0;
    }

    /**
     * Sumar stock (ej: compras).
     */
    public function increaseStock(int $qty): void
    {
        $this->current_stock += $qty;
        $this->save();
    }

    /**
     * Restar stock (ej: ventas).
     */
    public function decreaseStock(int $qty): void
    {
        $this->current_stock -= $qty;
        if ($this->current_stock < 0) {
            $this->current_stock = 0;
        }
        $this->save();
    }


    /* ============================================================
     |  HELPERS AVANZADOS: FEATURES E IMAGENES
     ============================================================ */

    /**
     * Retorna features como key-value.
     */
    public function featuresAsArray(): array
    {
        return $this->features()
            ->pluck('value', 'name')
            ->toArray();
    }

    /**
     * Primera imagen (portada).
     */
    public function defaultPicture()
    {
        return $this->pictures()->first();
    }

    /**
     * Mini JSON con info optimizada para listas de productos.
     */
    public function summary()
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'price'    => $this->finalPrice(),
            'on_sale'  => $this->isOnSale(),
            'stock'    => $this->current_stock,
            'image'    => optional($this->defaultPicture())->link,
        ];
    }

    /**
     * Información ampliada para detalle de producto.
     */
    public function fullDetail()
    {
        return [
            'id'          => $this->id,
            'store_id'    => $this->store_id,
            'category_id' => $this->category_id,
            'name'        => $this->name,
            'description' => $this->description,
            'price'       => $this->finalPrice(),
            'on_sale'     => $this->isOnSale(),
            'images'      => $this->pictures()->pluck('link'),
            'features'    => $this->featuresAsArray(),
            'stock'       => $this->current_stock,
        ];
    }
}

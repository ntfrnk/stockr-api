<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'store_id', 'user_id', 'client_id',
        'number', 'date', 'status',
        'subtotal', 'discount', 'tax', 'total',
        'payment_method', 'payment_reference', 'paid', 'notes'
    ];

    protected $casts = [
        'date' => 'datetime',
        'paid' => 'boolean',
    ];

    public function items() {
        return $this->hasMany(SaleItem::class);
    }
    
    public function store() {
        return $this->belongsTo(Store::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function payments() {
        return $this->hasMany(SalePayment::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'address',
        'city_id',
        'postal_code',
        'notes',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}

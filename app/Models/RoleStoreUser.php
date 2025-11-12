<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleStoreUser extends Model
{
    use HasFactory;

    protected $table = 'role_store_user';

    protected $fillable = ['role_id', 'store_id', 'user_id'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


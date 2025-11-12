<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_store_user')
            ->withPivot('store_id')
            ->withTimestamps();
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'role_store_user')
            ->withPivot('user_id')
            ->withTimestamps();
    }

    public function storeRoles()
    {
        return $this->hasMany(RoleStoreUser::class);
    }
}

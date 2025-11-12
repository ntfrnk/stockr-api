<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'detail', 'status'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_store_user')
            ->withPivot('role_id')
            ->withTimestamps();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_store_user')
            ->withPivot('user_id')
            ->withTimestamps();
    }

    public function storeRoles()
    {
        return $this->hasMany(RoleStoreUser::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'email',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'integer',
    ];

    /* ============================================================
     |  RELACIONES
     ============================================================ */

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    // Tiendas donde trabaja el usuario
    public function stores()
    {
        return $this->belongsToMany(Store::class, 'role_store_user')
            ->using(RoleStoreUser::class)
            ->withPivot('role_id')
            ->withTimestamps();
    }


    // Roles del usuario, pero globales (si existen)
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_store_user')
            ->withPivot('store_id')
            ->withTimestamps();
    }


    /* ============================================================
     |  HELPERS AVANZADOS – CONTROL DE ROLES POR TIENDA
     ============================================================ */

    /**
     * Obtener todos los roles que este usuario tiene en una tienda específica.
     */
    public function rolesInStore(int $storeId)
    {
        return $this->roles()
            ->wherePivot('store_id', $storeId)
            ->get();
    }

    /**
     * Saber si el usuario pertenece a una tienda.
     */
    public function isInStore(int $storeId): bool
    {
        return $this->stores()
            ->where('store_id', $storeId)
            ->exists();
    }

    /**
     * Saber si este usuario tiene un rol específico dentro de una tienda.
     *
     * Ej:
     * $user->hasRoleInStore('admin', 3)
     */
    public function hasRoleInStore(string $roleSlug, int $storeId): bool
    {
        return $this->roles()
            ->wherePivot('store_id', $storeId)
            ->where('slug', $roleSlug)
            ->exists();
    }

    /**
     * Saber si el usuario es administrador de una tienda.
     *
     * (Asumiendo un rol con slug 'admin')
     */
    public function isAdminOf(int $storeId): bool
    {
        return $this->hasRoleInStore('admin', $storeId);
    }

    /**
     * Obtener TODAS las tiendas donde este usuario es administrador.
     */
    public function adminStores()
    {
        return $this->stores()->whereHas('roles', function ($query) {
            $query->where('slug', 'admin');
        })->get();
    }

    /**
     * Obtener TODAS las tiendas donde el usuario trabaja (aunque no sea admin).
     */
    public function staffStores()
    {
        return $this->stores()->get();
    }

    /**
     * Obtener todos los roles del usuario como texto simple.
     *
     * Ej: ["admin", "seller", "editor"]
     */
    public function roleSlugs()
    {
        return $this->roles()->pluck('slug')->unique()->values();
    }

    /**
     * Saber si un usuario tiene *algún* rol entre varios posibles.
     *
     * Ej:
     * $user->hasAnyRoleInStore(['admin', 'seller'], $storeId)
     */
    public function hasAnyRoleInStore(array $slugs, int $storeId): bool
    {
        return $this->roles()
            ->wherePivot('store_id', $storeId)
            ->whereIn('slug', $slugs)
            ->exists();
    }

    /**
     * Saber si un usuario tiene *todos* los roles de un conjunto.
     */
    public function hasAllRolesInStore(array $slugs, int $storeId): bool
    {
        $count = $this->roles()
            ->wherePivot('store_id', $storeId)
            ->whereIn('slug', $slugs)
            ->count();

        return $count === count($slugs);
    }

    /**
     * Obtener información resumida del usuario dentro de una tienda.
     * Muy útil para APIs.
     */
    public function storeProfile(int $storeId)
    {
        if (!$this->isInStore($storeId)) {
            return null;
        }

        return [
            'user'  => $this->id,
            'email' => $this->email,
            'roles' => $this->rolesInStore($storeId)->pluck('slug'),
        ];
    }
}

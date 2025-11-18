<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
    ];

    /* ============================================================
     |  RELACIONES
     ============================================================ */

    /**
     * Usuarios que tienen este rol en alguna tienda.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_store_user')
            ->using(RoleStoreUser::class)
            ->withPivot('store_id')
            ->withTimestamps();
    }

    /**
     * Tiendas donde este rol está asignado.
     */
    public function stores()
    {
        return $this->belongsToMany(Store::class, 'role_store_user')
                    ->withPivot('user_id')
                    ->withTimestamps();
    }

    /**
     * Modelo pivote directo (por si se necesita manipular directamente).
     */
    public function assignments()
    {
        return $this->hasMany(RoleStoreUser::class);
    }


    /* ============================================================
     |  HELPERS AVANZADOS
     ============================================================ */

    /**
     * Verificar por slug si este rol es el esperado.
     */
    public function is(string $slug): bool
    {
        return $this->slug === $slug;
    }

    /**
     * Obtener todos los usuarios que tienen este rol en una tienda específica.
     */
    public function usersInStore(int $storeId)
    {
        return $this->users()
            ->wherePivot('store_id', $storeId)
            ->get();
    }

    /**
     * Saber si un usuario tiene este rol en una tienda.
     */
    public function userHasRole(int $userId, int $storeId): bool
    {
        return $this->assignments()
            ->where('user_id', $userId)
            ->where('store_id', $storeId)
            ->exists();
    }

    /**
     * Obtener todas las tiendas donde este rol está activo.
     */
    public function activeStores()
    {
        return $this->stores()->get();
    }

    /**
     * Ver si el rol está asignado al menos una vez.
     */
    public function isAssigned(): bool
    {
        return $this->assignments()->exists();
    }

    /**
     * Obtener un resumen para reportes o API.
     */
    public function summary()
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'users_count'  => $this->users()->count(),
            'stores_count' => $this->stores()->count(),
        ];
    }

    /**
     * Obtener todos los usuarios agrupados por tienda.
     * Útil para paneles de administración.
     */
    public function usersByStore()
    {
        return $this->assignments()
            ->with(['user', 'store'])
            ->get()
            ->groupBy('store_id');
    }
}

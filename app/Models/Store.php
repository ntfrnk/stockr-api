<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'detail',
        'status',
    ];

    /* ============================================================
     |  RELACIONES
     ============================================================ */

    public function contacts()
    {
        return $this->hasMany(StoreContact::class);
    }

    public function locations()
    {
        return $this->hasMany(StoreLocation::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_store_user')
            ->using(RoleStoreUser::class)
            ->withPivot('role_id')
            ->withTimestamps();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_store_user')
                    ->withPivot('user_id')
                    ->withTimestamps();
    }


    /* ============================================================
     |  HELPERS AVANZADOS
     ============================================================ */

    /**
     * Obtener la ubicación principal activa (primera por orden de ID).
     */
    public function primaryLocation()
    {
        return $this->locations()
            ->where('status', 1)
            ->orderBy('id')
            ->first();
    }

    /**
     * Obtener contactos activos de la tienda.
     */
    public function activeContacts()
    {
        return $this->contacts()
            ->where('status', 1)
            ->get();
    }

    /**
     * Obtener usuarios de la tienda que tengan un rol específico.
     */
    public function usersWithRole(string $roleSlug)
    {
        return $this->users()->whereHas('roles', function ($query) use ($roleSlug) {
            $query->where('slug', $roleSlug);
        })->get();
    }

    /**
     * Saber si un usuario pertenece a esta tienda.
     */
    public function hasUser(int $userId): bool
    {
        return $this->users()
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * Obtener todos los roles que un usuario tiene dentro de esta tienda.
     */
    public function rolesForUser(int $userId)
    {
        return $this->roles()
            ->wherePivot('user_id', $userId)
            ->get();
    }

    /**
     * Saber si un usuario tiene un rol específico dentro de esta tienda.
     */
    public function userHasRole(int $userId, string $roleSlug): bool
    {
        return $this->roles()
            ->wherePivot('user_id', $userId)
            ->where('slug', $roleSlug)
            ->exists();
    }

    /**
     * Saber si un usuario es administrador de la tienda.
     * (Asume un role con slug 'admin')
     */
    public function isAdmin(int $userId): bool
    {
        return $this->userHasRole($userId, 'admin');
    }

    /**
     * Obtener todos los colaboradores de la tienda.
     */
    public function staff()
    {
        return $this->users()->with('roles')->get();
    }

    /**
     * Obtener una dirección formateada de la ubicación principal.
     */
    public function formattedAddress()
    {
        $loc = $this->primaryLocation();

        if (!$loc) {
            return null;
        }

        return trim(
            "{$loc->street_name} {$loc->street_number}, " .
            "CP {$loc->postal_code}, " .
            "Ciudad {$loc->city}, Estado {$loc->state}, País {$loc->country}"
        );
    }
}

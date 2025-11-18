<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoleStoreUser extends Pivot
{
    use HasFactory;

    protected $table = 'role_store_user';

    protected $fillable = [
        'role_id',
        'store_id',
        'user_id',
    ];

    public $timestamps = true;

    /* ============================================================
     |  RELACIONES
     ============================================================ */

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


    /* ============================================================
     |  HELPERS AVANZADOS
     ============================================================ */

    /**
     * Chequear si el pivot corresponde a un rol dado.
     */
    public function isRole(string $slug): bool
    {
        return $this->role?->slug === $slug;
    }

    /**
     * Saber si el usuario es administrador general.
     */
    public function isAdmin(): bool
    {
        return $this->isRole('admin') || $this->isRole('superadmin');
    }

    /**
     * Saber si es colaborador.
     */
    public function isCollaborator(): bool
    {
        return $this->isRole('collaborator');
    }

    /**
     * Información resumida para API.
     */
    public function summary()
    {
        return [
            'role'  => $this->role?->name,
            'slug'  => $this->role?->slug,
            'store' => $this->store?->name,
            'user'  => $this->user?->name,
        ];
    }

    /**
     * Información detallada recomendada para dashboards.
     */
    public function fullDetail()
    {
        return [
            'role'  => $this->role,
            'store' => $this->store,
            'user'  => $this->user,
        ];
    }
}

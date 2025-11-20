<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Role;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * A User can have multiple Roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)
                    ->withTimestamps();
    }

    /**
     * Check if user has a specific role.
     *
     * @param string $roleName
     * @return bool
     */
    public function hasRole(string $roleName): bool
    {
        // Laad de rollen indien ze nog niet geladen zijn en controleer of de naam voorkomt.
        return $this->roles->contains('name', $roleName);
    }

    /**
     * Controleert of de gebruiker ten minste één van de opgegeven rollen heeft.
     *
     * @param string|array $roles De rolnaam of een array van rol-namen.
     * @return bool
     */
    public function hasAnyRole(string|array $roles): bool
    {
        // 1. Zorg ervoor dat $roles een array is, ook als er maar één string wordt doorgegeven.
        if (is_string($roles)) {
            $roles = [$roles];
        }

        // 2. Laad de rollen van de gebruiker. Als de collectie leeg is, false retourneren.
        if ($this->roles->isEmpty()) {
            return false;
        }

        // 3. Controleer of een van de opgegeven rol-namen voorkomt in de rollen van de gebruiker.
        // We gebruiken de Collection methode `contains` met een callback.
        return $this->roles->contains(function ($userRole) use ($roles) {

            // Controleer of de naam van de gebruikersrol voorkomt in de opgegeven lijst van rollen ($roles)
            return in_array($userRole->name, $roles);
        });
    }

    /**
     * Defineer de 'role_statuses_list' Accessor.
     * Hiermee kun je $user->role_statuses_list aanroepen.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function roleStatusesList(): Attribute
    {
        return Attribute::make(
            get: function () {
                // 1. Haal alle rollen op
                $allRoles = Role::all();

                // 2. Haal de ID's van de rollen die de gebruiker bezit op (snel doorzoekbaar)
                // We laden de relatie 'roles' hier expliciet indien deze nog niet geladen is
                $userRoleIds = $this->roles->pluck('id');

                $roleStatusesList = [];

                // 3. Loop door ALLE rollen en creëer een gestructureerd item
                foreach ($allRoles as $role) {

                    // Controleer of de ID van de huidige rol voorkomt in de lijst van gebruikersrollen
                    $isAssigned = $userRoleIds->contains($role->id);

                    $roleStatusesList[] = [
                        'role'     => $role,
                        'id'       => $role->id,
                        'name'     => $role->name,
                        'assigned' => $isAssigned,
                    ];
                }

                return $roleStatusesList;
            },
        );
    }

}

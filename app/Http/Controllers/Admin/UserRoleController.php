<?php

// app/Http/Controllers/Admin/UserRoleController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserRoleController extends Controller
{
    public function index()
    {
        $roles = Role::all(['id', 'name']);
        $users = User::with('roles')->get()->map(fn($u) => [
            'id'       => $u->id,
            'name'     => $u->name,
            'role_ids' => $u->roles->pluck('id'),
        ]);

        return Inertia::render('Admin/Roles', [
            'roles'       => $roles,
            'users'       => $users,
            'toggleRoute' => 'admin.roles.user.toggle',
        ]);
    }

    /**
     * Toon het formulier om de rollen van een gebruiker te bewerken.
     * De User $user wordt automatisch opgehaald door Route Model Binding.
     */
    public function edit(User $user)
    {
        // Haal alle beschikbare rollen op uit de database
        $roles = Role::all();

        // Haal de namen van de rollen van de huidige gebruiker op
        // Dit wordt gebruikt om de juiste checkboxes aan te vinken in de view
        $userRoles = $user->roles->pluck('name')->toArray();

        // Stuur de data naar de view
        return view('admin.users.roles-edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Verwerk de POST/PUT aanvraag en werk de rollen bij.
     */
    public function update(Request $request, Role $role, User $user)
    {
        $isAssigned = $request->is_assigned;

        if ($isAssigned)
        {
            $user->roles()->attach($role->id);
        }
        else
        {
            $user->roles()->detach($role->id);
        }

        $result = [
            'isAssigned' => $isAssigned,
            'message' => 'This is a message',
            'request' => $request
        ];
        return json_encode($result);
    }

}
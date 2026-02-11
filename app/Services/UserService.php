<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserService
{
    /**
     * Create a new user with assigned role.
     */
    public function createUser(array $data, string $roleName = 'Visitor'): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'avatar' => $data['avatar'] ?? null,
            'google_id' => $data['google_id'] ?? null,
        ]);

        $role = Role::findByName($roleName);
        $user->assignRole($role);

        return $user;
    }

    /**
     * Update user profile.
     */
    public function updateUser(User $user, array $data): User
    {
        $user->update([
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'avatar' => $data['avatar'] ?? $user->avatar,
        ]);

        if (isset($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        return $user;
    }

    /**
     * Get users by role.
     */
    public function getUsersByRole(string $roleName): \Illuminate\Database\Eloquent\Collection
    {
        return User::role($roleName)->get();
    }
}

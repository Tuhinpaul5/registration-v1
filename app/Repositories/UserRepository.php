<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function insert_user(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'location' => $data['location'] ?? 'Unknown',
        ]);
    }

    public function update_user(array $data): User
    {
        $user = User::find($data['id']);
        $user->name = $data['name'];
        $user->email = $data['email'];
        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();
        return $user;
    }

    public function delete_user(User $user): bool
    {
        return $user->delete();
    }

    public function get_user_by_id(int $email): ?User
    {
        return User::find($email);
    }
    public function get_user_by_email(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}
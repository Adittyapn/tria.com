<?php

namespace App\Filament\Auth;

use Filament\Pages\Auth\Register as BaseRegister;
use App\Models\User;

class Registration extends BaseRegister
{
    /**
     * Override proses pembuatan user saat register
     */
    protected function handleRegistration(array $data): User
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        // Assign default role customer
        $user->assignRole('customer');

        return $user;
    }

    /**
     * Optional: redirect setelah register
     */
    protected function getRedirectUrl(): string
    {
        return '/'; // atau Filament::getPanel('customer')->getUrl()
    }
}

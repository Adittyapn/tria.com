<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = Auth::user();

        if ($user->hasRole('super_admin')) {
            return redirect()->intended('/dashboard');
        }

        if ($user->hasRole('customer')) {
            return redirect()->intended('/');
        }

        return redirect()->intended('/');
    }
}

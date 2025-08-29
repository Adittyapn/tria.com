<?php

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LogoutResponse implements \Filament\Http\Responses\Auth\Contracts\LogoutResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        \Filament\Facades\Filament::auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
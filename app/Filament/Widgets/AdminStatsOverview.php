<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::role('customer')->count())
                ->description('Jumlah semua user')
                ->icon('heroicon-o-users')
                ->color('success'),

        ];
    }

     public static function canView(): bool
    {
        return Auth::user()?->hasRole('super_admin');
    }
}

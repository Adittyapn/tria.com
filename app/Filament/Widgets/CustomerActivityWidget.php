<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class CustomerActivityWidget extends Widget
{
    protected static string $view = 'filament.widgets.customer-activity-widget';
    
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $user = Auth::user();
        
        // Get last login from session
        $lastLogin = session('last_login_at', $user->created_at);
        
        // Get recent activities
        $recentActivities = Activity::where('causer_id', $user->id)
            ->where('causer_type', get_class($user))
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($activity) {
                return [
                    'description' => $activity->description,
                    'created_at' => $activity->created_at,
                    'icon' => $this->getActivityIcon($activity->description),
                    'color' => $this->getActivityColor($activity->description),
                ];
            });

        return [
            'lastLogin' => $lastLogin,
            'currentLogin' => now(),
            'userName' => $user->name,
            'userEmail' => $user->email,
            'recentActivities' => $recentActivities,
        ];
    }

    protected function getActivityIcon(string $description): string
    {
        return match (true) {
            str_contains(strtolower($description), 'login') => 'heroicon-m-arrow-right-on-rectangle',
            str_contains(strtolower($description), 'order') => 'heroicon-m-shopping-bag',
            str_contains(strtolower($description), 'payment') => 'heroicon-m-credit-card',
            str_contains(strtolower($description), 'profile') => 'heroicon-m-user',
            default => 'heroicon-m-information-circle',
        };
    }

    protected function getActivityColor(string $description): string
    {
        return match (true) {
            str_contains(strtolower($description), 'login') => 'success',
            str_contains(strtolower($description), 'order') => 'info',
            str_contains(strtolower($description), 'payment') => 'warning',
            str_contains(strtolower($description), 'profile') => 'primary',
            default => 'gray',
        };
    }

    public static function canView(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        
        // Periksa apakah user memiliki role customer melalui database
        return DB::table('model_has_roles')
            ->where('model_id', $user->id)
            ->where('model_type', get_class($user))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('roles')
                    ->whereRaw('roles.id = model_has_roles.role_id')
                    ->where('roles.name', 'customer');
            })
            ->exists();
    }
}

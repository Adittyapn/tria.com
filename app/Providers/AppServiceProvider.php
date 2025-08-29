<?php

namespace App\Providers;

use App\Policies\ActivityPolicy;
use BezhanSalleh\FilamentShield\FilamentShield;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;

class AppServiceProvider extends ServiceProvider
{
    protected array $policies = [
        Activity::class => ActivityPolicy::class,
    ];

    public function register(): void
    {
            $this->app->singleton(RajaOngkirService::class, function ($app) {
        return new RajaOngkirService();
    });
        // TAMBAHKAN KODE INI
        $this->app->bind(
            \Filament\Http\Responses\Auth\Contracts\LogoutResponse::class,
            \App\Http\Responses\LogoutResponse::class
        );

        $this->app->bind(
            \Filament\Http\Responses\Auth\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );
    }


    public function boot(): void
    {
        $this->configurePolicies();

        $this->configureDB();

        $this->configureModels();

        $this->configureFilament();
    }

    private function configurePolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }

    private function configureDB(): void
    {
        DB::prohibitDestructiveCommands($this->app->environment('production'));
    }

    private function configureModels(): void
    {
        Model::preventAccessingMissingAttributes();

        Model::unguard();
    }

    private function configureFilament(): void
    {
        FilamentShield::prohibitDestructiveCommands($this->app->environment('production'));

        Table::configureUsing(fn (Table $table) => $table->paginationPageOptions([10, 25, 50]));
    }
}

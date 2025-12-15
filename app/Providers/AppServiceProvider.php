<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Register any application services.
     */
    public function register(): void
    {
        Model::unguard();
    }

    /**
     * Bootstrap any application services.
     */

    public function boot(): void
{
    Inertia::share([
        'auth' => function () {
            $user = Auth::user();
            if ($user) {
                // Debug completo
                \Log::info('Usuario autenticado:', [
                    'id' => $user->id,
                    'roles' => $user->roles->pluck('name'),
                    'permisos_directos' => $user->permissions->pluck('name'),
                    'todos_permisos' => $user->getAllPermissions()->pluck('name'),
                ]);

                $permissions = $user->getAllPermissions()->pluck('name')->toArray();
                
                return [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->nombres . ' ' . $user->apellidos,
                        'permissions' => $permissions,
                        'roles' => $user->roles->pluck('name')->toArray(), // Agregado
                    ]
                ];
            }
            return null;
        },
    ]);

    $this->bootRoute();
}


    public function bootRoute(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}

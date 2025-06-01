<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class MoonShinePageCheckPermission
{
    public function handle($request, Closure $next)
    {
        if (($request->is('admin/login')) || ($request->is('admin/authenticate'))) {
            return $next($request);
        }

        $user = Auth::guard('moonshine')->user();

        if (!$user) {
            Log::info('Попытка доступа без авторизации. Редирект на login.');
            return redirect('/admin/login');
        }

        $fullUrl = $request->fullUrl();

        $resourcePermissions = [
            'user-resource' => 'manage_users',
            'role-resource' => 'manage_users',
            'product-resource' => 'manage_products',
            'order-resource' => 'manage_orders',
            'order-item-resource' => 'manage_orders',
        ];
        Log::info($fullUrl);

        $matchedResource = array_filter(array_keys($resourcePermissions), fn($resource) => str_contains($fullUrl, $resource));
        
        Log::info(str_contains($request->fullUrl(), 'order-item-resource'));
        Log::info($matchedResource);
        Log::info(!empty($matchedResource));
        
        if (!empty($matchedResource)) {
            $resourceKey = reset($matchedResource);
            $permission = $resourcePermissions[$resourceKey] ?? null;
        
            if ($permission && !Gate::allows($permission, $user)) {
                Log::info("Доступ запрещен: {$resourceKey}, Пользователь: {$user->name}");
                abort(403, 'Access denied');
            }
        }

        return $next($request);
    }
}

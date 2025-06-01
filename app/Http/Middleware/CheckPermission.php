<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next): Response
    {
        if (($request->is('admin/login')) || ($request->is('admin/authenticate'))) {
            return $next($request);
        }

        // Проверка прав доступа, если пользователь не авторизован или не имеет нужных разрешений
        $user = Auth::guard('moonshine')->user();

        if (!$user || !$user->hasPermission('access_dashboard')) {
            return redirect('/'); // Либо можно использовать: abort(403, 'Access denied.');
        }

        return $next($request);
    }
}

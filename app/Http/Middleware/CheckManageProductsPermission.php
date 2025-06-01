<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckManageProductsPermission
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Если пользователь не авторизован или его роль не "seller", отправляем на главную
        if (!$user || $user->role->role_name !== 'Seller') {
            return redirect('/');
        }

        return $next($request);
    }
}

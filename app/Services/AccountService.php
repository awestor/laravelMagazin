<?php
namespace App\Services;

use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AccountService
{
    public function updateProfile(User $user, array $data)
    {
        foreach ($data as $field => $value) {
            if ($user->$field !== $value) {
                $user->$field = $value;
            }
        }
        $user->save();
        return ['message' => 'Данные обновлены!', 'status' => true];
    }

    public function updatePassword(User $user, array $data)
    {
        if (!Hash::check($data['old_password'], $user->password)) {
            return ['message' => 'Неверный старый пароль', 'status' => false];
        }

        $user->password = Hash::make($data['new_password']);
        $user->save();
        return ['message' => 'Пароль успешно изменён!', 'status' => true];
    }

    public function getUserOrders()
    {
        return Auth::user()->orders()
            ->orderByDesc('created_at')
            ->with('items')
            ->get();
    }

    public function calculateTotalSpent($orders)
    {
        return $orders->sum(fn($order) => $order->items->sum('price'));
    }

    public function getViewData(): array
    {
        return [
            'styles' => [
                'css/header.css',
                'css/pages/account.css',
            ],
            'scripts' => [
                'js/pages/account.js',
            ],
        ];
    }
}

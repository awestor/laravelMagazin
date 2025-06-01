<?php
namespace App\Services;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthService
{
    public function login(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            $redirectUrl = Session::get('redirect_after_login', route('home'));
            Session::forget('redirect_after_login');
            return redirect()->to($redirectUrl);
        }

        return back()->withErrors(['error' => 'Неправильный email или пароль']);
    }

    public function register(array $data)
    {
        $customerRole = Role::where('role_name', 'Customer')->first();

        $user = User::create([
            'name' => $data['name'],
            'login' => $data['login'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $customerRole->role_id ?? 1,
        ]);

        Auth::login($user);

        return redirect()->route('MainPage.index')->with('message', 'Вы успешно зарегистрировались!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('MainPage.index');
    }

    public function getAssets(string $page): array
    {
        $styles = ['css/header.css'];
        $scripts = [];

        if ($page === 'login') {
            $styles[] = 'css/pages/login.css';
        }

        if ($page === 'register') {
            $styles[] = 'css/pages/register.css';
        }

        return compact('styles', 'scripts');
    }
}

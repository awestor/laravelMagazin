<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'login' => 'string|max:255',
            'email' => 'email|max:255|unique:users,email,' . auth()->id(),
            'old_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Имя должно быть строкой.',
            'login.string' => 'Логин должен быть строкой.',
            'email.email' => 'Введите корректный email.',
            'new_password.min' => 'Новый пароль должен содержать минимум 6 символов.',
            'new_password.confirmed' => 'Пароли не совпадают.',
        ];
    }
}

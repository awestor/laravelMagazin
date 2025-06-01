<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            '*.orderItemId' => 'required|integer|exists:order_items,id',
            '*.quantity' => 'required|integer|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            '*.orderItemId.required' => 'ID товара обязателен!',
            '*.orderItemId.exists' => 'Товар не найден!',
            '*.quantity.required' => 'Количество не может быть пустым!',
            '*.quantity.min' => 'Минимальное количество — 1!',
        ];
    }
}

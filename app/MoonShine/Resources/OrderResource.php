<?php
namespace App\MoonShine\Resources;

use App\Models\Order;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Components\Layout\Box;

/**
 * @extends ModelResource<Order>
 */
class OrderResource extends ModelResource
{
    protected string $model = Order::class;
    protected string $title = 'Заказы';

    protected function indexFields(): iterable
    {
        return [
            ID::make('ID', 'order_id')->readonly()->sortable(),
            BelongsTo::make('Пользователь', 'user', 'name'),
            Number::make('Сумма', 'total_price', function ($order) {
                return $order->items()->sum('price');
            })->required(),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make('ID', 'order_id')->readonly(),
                BelongsTo::make('Пользователь', 'user', 'name')->required(),
                Number::make('Сумма', 'total_price')->required(),
            ])
        ];
    }

    protected function detailFields(): iterable
    {
        return [
            ID::make('ID', 'order_id')->readonly(),
            BelongsTo::make('Пользователь', 'user', 'name'),
            Number::make('Сумма', 'total_price'),
        ];
    }

    protected function rules(mixed $item): array
    {
        return [
            'total_price' => ['required', 'numeric', 'min:0'],
        ];
    }
}

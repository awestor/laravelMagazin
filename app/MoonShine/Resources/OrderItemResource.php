<?php

namespace App\MoonShine\Resources;

use App\Models\OrderItem;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Components\Layout\Box;

/**
 * @extends ModelResource<OrderItem>
 */
class OrderItemResource extends ModelResource
{
    protected string $model = OrderItem::class;
    protected string $title = 'Элементы заказа';

    protected function indexFields(): iterable
    {
        return [
            ID::make('ID', 'order_item_id')->readonly()->sortable(),
            BelongsTo::make('Заказ', 'order'),
            BelongsTo::make('Товар', 'product'),
            Number::make('Количество', 'quantity')->required(),
            Number::make('Цена', 'price')->required(),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make('ID', 'order_item_id')->readonly(),
                BelongsTo::make('Заказ', 'order')->required(),
                BelongsTo::make('Товар', 'product')->required(),
                Number::make('Количество', 'quantity')->required(),
                Number::make('Цена', 'price')->required(),
            ])
        ];
    }

    protected function detailFields(): iterable
    {
        return [
            ID::make('ID', 'order_item_id')->readonly(),
            BelongsTo::make('Заказ', 'order'),
            BelongsTo::make('Товар', 'product'),
            Number::make('Количество', 'quantity'),
            Number::make('Цена', 'price'),
        ];
    }
}

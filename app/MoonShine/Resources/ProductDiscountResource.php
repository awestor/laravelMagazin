<?php

namespace App\MoonShine\Resources;

use App\Models\ProductDiscount;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Fields\ID;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Components\Layout\Box;

/**
 * @extends ModelResource<ProductDiscount>
 */
class ProductDiscountResource extends ModelResource
{
    protected string $model = ProductDiscount::class;
    protected string $title = 'Скидки на товары';

    protected function indexFields(): iterable
    {
        return [
            ID::make('ID', 'product_discounts_id')->readonly(),
            BelongsTo::make('Товар', 'product', 'name')->required(),
            BelongsTo::make('Скидка', 'discount', 'discount_type')->required(),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make('ID', 'product_discounts_id')->readonly(),
                BelongsTo::make('Товар', 'product', 'name')->required(),
                BelongsTo::make('Скидка', 'discount', 'discount_type')->required(),
            ])
        ];
    }

    protected function detailFields(): iterable
    {
        return [
            ID::make('ID', 'product_discounts_id')->readonly(),
            BelongsTo::make('Товар', 'product', 'name'),
            BelongsTo::make('Скидка', 'discount', 'discount_type'),
        ];
    }

    protected function rules(mixed $item): array
    {
        return [
            'product_id' => ['required', 'exists:products,product_id'],
            'discount_id' => ['required', 'exists:discounts,discount_id'],
        ];
    }
}

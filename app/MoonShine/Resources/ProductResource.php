<?php
namespace App\MoonShine\Resources;

use App\Models\Product;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Number;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Components\Layout\Box;

/**
 * @extends ModelResource<Product>
 */
class ProductResource extends ModelResource
{
    protected string $model = Product::class;
    protected string $title = 'Товары';

    protected function indexFields(): iterable
    {
        return [
            ID::make('ID', 'product_id')->readonly()->sortable(),
            Text::make('Название', 'name')->required()->sortable(),
            Number::make('Цена', 'price')->required(),
            BelongsTo::make('Категория', 'category', 'category_name')->required(),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make('ID', 'product_id')->readonly(),
                Text::make('Название', 'name')->required(),
                Number::make('Цена', 'price')->required(),
                BelongsTo::make('Категория', 'category', 'category_name')->required(),
            ])
        ];
    }

    protected function detailFields(): iterable
    {
        return [
            ID::make('ID', 'product_id')->readonly(),
            Text::make('Название', 'name'),
            Number::make('Цена', 'price'),
            BelongsTo::make('Категория', 'category', 'category_name'),
        ];
    }

    protected function rules(mixed $item): array
    {
        return [
            'name' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }
}

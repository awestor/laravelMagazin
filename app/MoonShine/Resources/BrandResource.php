<?php

namespace App\MoonShine\Resources;

use App\Models\Brand;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Components\Layout\Box;

/**
 * @extends ModelResource<Brand>
 */
class BrandResource extends ModelResource
{
    protected string $model = Brand::class;
    protected string $title = 'Бренды';

    protected function indexFields(): iterable
    {
        return [
            ID::make('ID', 'brand_id')->readonly()->sortable(),
            Text::make('Название', 'brand_name')->required()->sortable(),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make('ID', 'brand_id')->readonly(),
                Text::make('Название', 'brand_name')->required(),
            ])
        ];
    }

    protected function detailFields(): iterable
    {
        return [
            ID::make('ID', 'brand_id')->readonly(),
            Text::make('Название', 'brand_name'),
        ];
    }

    protected function rules(mixed $item): array
    {
        return [
            'name' => ['required', 'string'],
        ];
    }
}

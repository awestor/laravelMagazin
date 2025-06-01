<?php

namespace App\MoonShine\Resources;

use App\Models\Discount;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Date;


use MoonShine\UI\Components\Layout\Box;

/**
 * @extends ModelResource<Discount>
 */
class DiscountResource extends ModelResource
{
    protected string $model = Discount::class;
    protected string $title = 'Скидки';

    protected function indexFields(): iterable
    {
        return [
            ID::make('ID', 'discount_id')->readonly()->sortable(),
            Text::make('Тип скидки', 'discount_type')->required()->sortable(),
            Number::make('Размер скидки', 'discount_value')->required(),
            Date::make('Начало', 'start_date'),
            Date::make('Окончание', 'end_date'),
            Switcher::make('Активна', 'is_active')->default(true),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make('ID', 'discount_id')->readonly()->sortable(),
                Text::make('Тип скидки', 'discount_type')->required()->sortable(),
                Number::make('Размер скидки', 'discount_value')->required(),
                Text::make('Начало', 'start_date')
                    ->customAttributes(['type' => 'datetime-local', 'value' => now()->format('Y-m-d.H:i')]),

                Text::make('Окончание', 'end_date')
                    ->customAttributes(['type' => 'datetime-local', 'value' => now()->addDays(7)->format('Y-m-d.H:i')]),

                Switcher::make('Активна', 'is_active')->default(true),
            ])
        ];
    }
}

<?php
namespace App\MoonShine\Resources;

use App\Models\UserDiscount;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Fields\ID;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Components\Layout\Box;

/**
 * @extends ModelResource<UserDiscount>
 */
class UserDiscountResource extends ModelResource
{
    protected string $model = UserDiscount::class;
    protected string $title = 'Скидки пользователей';

    protected function indexFields(): iterable
    {
        return [
            ID::make('ID', 'user_discounts_id')->readonly(),
            BelongsTo::make('Пользователь', 'user', 'name')->required(),
            BelongsTo::make('Скидка', 'discount', 'discount_type')->required(),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make('ID', 'user_discounts_id')->readonly(),
                BelongsTo::make('Пользователь', 'user', 'name')->required(),
                BelongsTo::make('Скидка', 'discount', 'discount_type')->required(),
            ])
        ];
    }

    protected function detailFields(): iterable
    {
        return [
            ID::make('ID', 'user_discounts_id')->readonly(),
            BelongsTo::make('Пользователь', 'user', 'name'),
            BelongsTo::make('Скидка', 'discount', 'discount_type'),
        ];
    }

    protected function rules(mixed $item): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'discount_id' => ['required', 'exists:discounts,discount_id'],
        ];
    }
}

<?php
namespace App\MoonShine\Resources;

use App\Models\Category;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\UI\Components\Layout\Box;

/**
 * @extends ModelResource<Category>
 */
class CategoryResource extends ModelResource
{
    protected string $model = Category::class;
    protected string $title = 'Категории';

    protected function indexFields(): iterable
    {
        return [
            ID::make('ID', 'category_id')->readonly()->sortable(),
            Text::make('Название категории', 'category_name')->required()->sortable(),
            Text::make('Тип категории', 'category_type')->sortable(),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make('ID', 'category_id')->readonly(),
                Text::make('Название категории', 'category_name')->required(),
            ])
        ];
    }

    protected function detailFields(): iterable
    {
        $fields = [
            ID::make('ID', 'category_id')->readonly(),
            Text::make('Название категории', 'category_name'),
            Text::make('Тип категории', 'category_type'),
            HasMany::make('Товары', 'products', 'name'), // ✅ Всегда показываем товары
        ];
    
        // ✅ Если категория НЕ LEAF, добавляем связь с подкатегориями
        if ($this->getItem()->category_type !== 'LEAF') {
            $fields[] = HasMany::make('Подкатегории', 'children', 'category_name', CategoryResource::class);
        }
    
        return $fields;
    }

    protected function rules(mixed $item): array
    {
        return [
            'category_name' => ['required', 'string', 'max:100'],
        ];
    }
}

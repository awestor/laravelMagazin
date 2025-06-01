<?php
namespace App\MoonShine\Resources;

use App\Models\Role;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\Laravel\Fields\Relationships\HasMany;

/**
 * @extends ModelResource<Role>
 */
class RoleResource extends ModelResource
{
    protected string $model = Role::class;
    protected string $title = 'Роли';

    protected function indexFields(): iterable
    {
        return [
            ID::make()->readonly()->sortable(),
            Text::make('Название роли', 'role_name')->required()->sortable(),
            HasMany::make('Пользователи', 'users', 'name'),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make()->readonly(),
                Text::make('Название роли', 'role_name')->required(),
            ])
        ];
    }

    protected function detailFields(): iterable
    {
        return [
            ID::make()->readonly(),
            Text::make('Название роли', 'role_name'),
            HasMany::make('Пользователи', 'users', 'name'),
        ];
    }

    protected function rules(mixed $item): array
    {
        return [
            'role_name' => ['required', 'string', 'max:50'],
        ];
    }
}

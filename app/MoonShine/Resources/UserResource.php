<?php
namespace App\MoonShine\Resources;

use App\Models\User;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\HasMany;

/**
 * @extends ModelResource<User>
 */
class UserResource extends ModelResource
{
    protected string $model = User::class;
    protected string $title = 'Пользователи';

    protected function indexFields(): iterable
    {
        return [
            ID::make()->readonly()->sortable(),
            Text::make('Имя', 'name')->sortable(),
            Text::make('Email', 'email')->sortable()->required(),
            BelongsTo::make('Роль', 'role', 'role_name'),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make()->readonly(),
                Text::make('Имя', 'name')->required(),
                Text::make('Логин', 'login')->required(),
                Text::make('Email', 'email')->required(),
                Text::make('Пароль', 'password')->required(),
                BelongsTo::make('Роль', 'role', 'role_name')->required(),
            ]),
            
        ];
    }

    protected function detailFields(): iterable
    {
        return [
            ID::make()->readonly(),
            Text::make('Имя', 'name'),
            Text::make('Email', 'email'),
            BelongsTo::make('Роль', 'role', 'role_name'),
        ];
    }

    protected function rules(mixed $item): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'login' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }
}

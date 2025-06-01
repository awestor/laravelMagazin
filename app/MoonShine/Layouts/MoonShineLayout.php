<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Laravel\Components\Layout\{Locales, Notifications, Profile, Search};
use MoonShine\UI\Components\{Breadcrumbs,
    Components,
    Layout\Flash,
    Layout\Div,
    Layout\Body,
    Layout\Burger,
    Layout\Content,
    Layout\Footer,
    Layout\Head,
    Layout\Favicon,
    Layout\Assets,
    Layout\Meta,
    Layout\Header,
    Layout\Html,
    Layout\Layout,
    Layout\Logo,
    Layout\Menu,
    Layout\Sidebar,
    Layout\ThemeSwitcher,
    Layout\TopBar,
    Layout\Wrapper,
    When};
use App\MoonShine\Resources\UserResource;
use MoonShine\MenuManager\MenuItem;
use App\MoonShine\Resources\RoleResource;
use App\MoonShine\Resources\ProductResource;
use App\MoonShine\Resources\OrderResource;
use App\MoonShine\Resources\CategoryResource;
use App\MoonShine\Resources\BrandResource;
use App\MoonShine\Resources\OrderItemResource;
use App\MoonShine\Resources\DiscountResource;
use App\MoonShine\Resources\ProductDiscountResource;
use App\MoonShine\Resources\UserDiscountResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

final class MoonShineLayout extends AppLayout
{
    protected function assets(): array
    {
        return [
            ...parent::assets(),
        ];
    }

    protected function menu(): array
    {
        $user = Auth::guard('moonshine')->user();
        return array_filter([
            ...parent::menu(),
            Gate::allows('manage_users', $user) ? MenuItem::make('Users', UserResource::class) : null,
            Gate::allows('manage_users', $user) ? MenuItem::make('Roles', RoleResource::class) : null,
            Gate::allows('manage_products', $user) ? MenuItem::make('Products', ProductResource::class) : null,
            Gate::allows('manage_orders', $user) ? MenuItem::make('Orders', OrderResource::class) : null,
            MenuItem::make('Categories', CategoryResource::class),
            MenuItem::make('Brands', BrandResource::class),
            Gate::allows('manage_orders', $user) ? MenuItem::make('OrderItems', OrderItemResource::class) : null,
            MenuItem::make('Discounts', DiscountResource::class),
            MenuItem::make('ProductDiscounts', ProductDiscountResource::class),
            MenuItem::make('UserDiscounts', UserDiscountResource::class),
        ]);
    }

    /**
     * @param ColorManager $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

        // $colorManager->primary('#00000');
    }

    public function build(): Layout
    {
        return parent::build();
    }
}

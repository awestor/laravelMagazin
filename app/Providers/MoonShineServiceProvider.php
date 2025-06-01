<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShine;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;
use App\MoonShine\Resources\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRoleResource;
use App\MoonShine\Resources\UserResource;
use App\MoonShine\Resources\RoleResource;
use App\MoonShine\Resources\ProductResource;
use App\MoonShine\Resources\OrderResource;
use App\MoonShine\Resources\CategoryResource;
use App\MoonShine\Resources\BrandResource;
use App\MoonShine\Resources\OrderItemResource;
use App\MoonShine\Resources\DiscountResource;
use App\MoonShine\Resources\ProductDiscountResource;
use App\MoonShine\Resources\UserDiscountResource;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  MoonShine  $core
     * @param  MoonShineConfigurator  $config
     *
     */
    public function boot(CoreContract $core, ConfiguratorContract $config): void
    {
        $core
            ->resources([
                MoonShineUserResource::class,
                MoonShineUserRoleResource::class,
                UserResource::class,
                RoleResource::class,
                ProductResource::class,
                OrderResource::class,
                CategoryResource::class,
                BrandResource::class,
                OrderItemResource::class,
                DiscountResource::class,
                ProductDiscountResource::class,
                UserDiscountResource::class,
            ])
            ->pages([
                ...$config->getPages(),
            ])
        ;
    }
}

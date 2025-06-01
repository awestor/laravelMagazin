<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Таблица ролей
        Schema::create('roles', function (Blueprint $table) {
            $table->id('role_id');
            $table->string('role_name', 50)->comment("Например: 'Покупатель', 'Менеджер склада', 'Курьер', 'Администратор'");
            $table->timestamps();
        });

        // Таблица разрешений
        Schema::create('permissions', function (Blueprint $table) {
            $table->id('permission_id');
            $table->string('permission_name', 100)->comment("Например: 'Создавать заказы', 'Просматривать отчёты', 'Обрабатывать возвраты'");
            $table->timestamps();
        });

        // Таблица брендов
        Schema::create('brands', function (Blueprint $table) {
            $table->id('brand_id');
            $table->string('brand_name', 100);
            $table->timestamps();
        });

        // Таблица категорий (с рекурсивной связью)
        Schema::create('categories', function (Blueprint $table) {
            $table->id('category_id'); // Создаёт auto-increment первичный ключ
            $table->string('category_name', 100);
            
            // ENUM поле с возможными значениями и значением по умолчанию
            $table->enum('category_type', ['ROOT', 'PARENT', 'LEAF'])->default('LEAF');
            
            // Рекурсивная связь с родительской категорией
            $table->unsignedBigInteger('parent_category_id')->nullable();
            $table->foreign('parent_category_id')
                  ->references('category_id')
                  ->on('categories')
                  ->nullOnDelete();

            $table->boolean('category_hiden')
                  ->default(true);
            $table->timestamps(); // created_at и updated_at
        });

        // Таблица складов
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id('warehouse_id');
            $table->string('location', 255);
            $table->unsignedBigInteger('director_id')->nullable();
            $table->string('stock_status', 100)->comment("Наличие товаров на складе");
            $table->timestamps();
        });

        // Таблица пунктов выдачи
        Schema::create('pickup_points', function (Blueprint $table) {
            $table->id('pickup_id');
            $table->string('location', 255);
            $table->time('opening_time')->comment("Время начала работы");
            $table->time('closing_time')->comment("Время окончания работы");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pickup_points');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
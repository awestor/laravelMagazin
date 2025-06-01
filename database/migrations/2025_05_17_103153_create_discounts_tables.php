<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Таблица скидок
        Schema::create('discounts', function (Blueprint $table) {
            $table->id('discount_id');
            $table->string('discount_type', 50)->comment("Типы: 'Акция', 'Персональная', 'Сезонная'");
            $table->decimal('discount_value', 10, 2)->comment("Может быть % или фикс. суммой");
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->boolean('is_active')->default(true)->comment("Активна ли скидка");
            $table->timestamps();
        });

        // Таблица скидок на продукты
        Schema::create('product_discounts', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('discount_id');

            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->foreign('discount_id')->references('discount_id')->on('discounts')->onDelete('cascade');

            $table->primary(['product_id', 'discount_id']);
        });

        // Таблица персональных скидок пользователей
        Schema::create('user_discounts', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('discount_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('discount_id')->references('discount_id')->on('discounts')->onDelete('cascade');

            $table->primary(['user_id', 'discount_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_discounts');
        Schema::dropIfExists('product_discounts');
        Schema::dropIfExists('discounts');
    }
};
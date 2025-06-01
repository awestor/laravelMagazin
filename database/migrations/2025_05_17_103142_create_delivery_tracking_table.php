<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Таблица отслеживания доставки
        Schema::create('delivery_tracking', function (Blueprint $table) {
            $table->id('delivery_id');
            $table->unsignedBigInteger('order_item_id');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('courier_id')->nullable();
            $table->unsignedBigInteger('pickup_point_id');
            $table->string('delivery_status', 50)->comment("Статусы: 'В пути', 'Прибыл на склад', 'Выдан'");
            $table->timestamp('estimated_arrival')->nullable();
            $table->timestamp('actual_arrival')->nullable()->comment("Фактическая дата доставки");
            $table->timestamps();

            $table->foreign('order_item_id')->references('order_item_id')->on('order_items');
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses');
            $table->foreign('courier_id')->references('id')->on('users');
            $table->foreign('pickup_point_id')->references('pickup_id')->on('pickup_points');
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_tracking');
    }
};
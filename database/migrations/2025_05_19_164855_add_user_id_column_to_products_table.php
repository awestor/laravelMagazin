<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Добавляем поле user_id, которое может быть NULL
            $table->unsignedBigInteger('user_id')->nullable();
            
            // Создаем внешний ключ
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade'); // или 'set null' потом посмотрю как будет)
        });
        Schema::table('images', function (Blueprint $table) {
            $table->enum('image_type', ['MAIN', 'INFO'])->default('INFO');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
        Schema::table('images', function (Blueprint $table) {
            $table->dropColumn('image_type');
        });
    }
};
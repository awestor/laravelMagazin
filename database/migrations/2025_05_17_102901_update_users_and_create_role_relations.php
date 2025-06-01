<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Обновление таблицы пользователей
        Schema::table('users', function (Blueprint $table) {
            $table->string('login')->after('name');
            $table->unsignedBigInteger('role_id')->after('password');
            
            $table->foreign('role_id')->references('role_id')->on('roles');
        });

        // Добавляем внешний ключ для директора склада
        Schema::table('warehouses', function (Blueprint $table) {
            $table->foreign('director_id')->references('id')->on('users');
        });

        // Таблица связей ролей и разрешений
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('permission_id');

            $table->foreign('role_id')->references('role_id')->on('roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('permission_id')->on('permissions')->onDelete('cascade');

            $table->primary(['role_id', 'permission_id']);
        });
    }

    public function down()
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropForeign(['director_id']);
        });

        Schema::dropIfExists('role_permissions');
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['login', 'role_id']);
        });
    }
};
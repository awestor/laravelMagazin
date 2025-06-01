<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Очистка существующих данных
        DB::table('role_permissions')->delete();
        DB::table('roles')->delete();
        DB::table('permissions')->delete();

        // Вставка привилегий с комментариями
        $permissions = [
            // Управление товарами (создание, редактирование, удаление)
            ['permission_name' => 'manage_products'],
            
            // Управление категориями товаров
            ['permission_name' => 'manage_categories'],
            
            // Управление заказами (просмотр, изменение статуса)
            ['permission_name' => 'manage_orders'],
            
            // Управление пользователями (создание, редактирование, блокировка)
            ['permission_name' => 'manage_users'],
            
            // Просмотр аналитических отчетов и статистики
            ['permission_name' => 'view_reports'],
            
            // Управление платежами и возвратами
            ['permission_name' => 'manage_payments'],
            
            // Модерация отзывов и рейтингов
            ['permission_name' => 'manage_reviews'],
            
            /**
             * Доступ к административной панели (dashboard)
             * 
             * Эта привилегия контролирует доступ к главной админ-панели marketplace.
             * Даже если у пользователя есть другие права, без этой привилегии он не сможет
             * увидеть административный интерфейс. Это важный уровень безопасности, который
             * предотвращает доступ обычных пользователей к админ-разделу.
             * 
             * В dashboard обычно входят:
             * - Общая статистика продаж
             * - Графики активности
             * - Быстрый доступ к основным функциям
             * - Уведомления системы
             */
            ['permission_name' => 'access_dashboard'],
        ];
        DB::table('permissions')->insert($permissions);

        // Вставка ролей
        $roles = [
            ['role_name' => 'Admin'],        // Полный доступ
            ['role_name' => 'Seller'],        // Продавцы товаров
            ['role_name' => 'Customer'],      // Обычные покупатели
            ['role_name' => 'Moderator'],     // Модераторы контента
            ['role_name' => 'Content Manager'], // Управление каталогом
        ];
        DB::table('roles')->insert($roles);

        // Назначение привилегий ролям
        $rolePermissions = [
            // Admin - все привилегии
            ['role_id' => 1, 'permission_id' => 1],
            ['role_id' => 1, 'permission_id' => 2],
            ['role_id' => 1, 'permission_id' => 3],
            ['role_id' => 1, 'permission_id' => 4],
            ['role_id' => 1, 'permission_id' => 5],
            ['role_id' => 1, 'permission_id' => 6],
            ['role_id' => 1, 'permission_id' => 7],
            ['role_id' => 1, 'permission_id' => 8], // access_dashboard
            
            // Seller - привилегии для работы с товарами и заказами
            ['role_id' => 2, 'permission_id' => 1], // manage_products
            ['role_id' => 2, 'permission_id' => 3], // manage_orders
            ['role_id' => 2, 'permission_id' => 7], // manage_reviews
            ['role_id' => 2, 'permission_id' => 8], // access_dashboard (личный кабинет продавца)
            
            // Moderator - привилегии для модерации
            ['role_id' => 4, 'permission_id' => 1], // manage_products
            ['role_id' => 4, 'permission_id' => 2], // manage_categories
            ['role_id' => 4, 'permission_id' => 7], // manage_reviews
            ['role_id' => 4, 'permission_id' => 8], // access_dashboard
            
            // Content Manager - управление каталогом
            ['role_id' => 5, 'permission_id' => 2], // manage_categories
            ['role_id' => 5, 'permission_id' => 7], // manage_reviews
            ['role_id' => 5, 'permission_id' => 8], // access_dashboard
        ];
        
        // Обычные покупатели (Customer) не получают никаких специальных привилегий
        DB::table('role_permissions')->insert($rolePermissions);
    }
}
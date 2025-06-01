<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->truncate();

        $now = Carbon::now();

        $users = [
            // 1. Администратор системы
            [
                'name' => 'Екатерина Администратова',
                'login' => 'admin_ekaterina',
                'email' => 'admin@marketplace.ru',
                'password' => Hash::make('AdminPass123!'),
                'role_id' => 1, // Admin
                'created_at' => $now,
            ],
            
            // 2. Продавец электроники
            [
                'name' => 'Иван Электроников',
                'login' => 'tech_seller',
                'email' => 'electronics@marketplace.ru',
                'password' => Hash::make('TechSeller456!'),
                'role_id' => 2, // Seller
                'created_at' => $now->subDays(10),
            ],
            
            // 3. Обычный покупатель
            [
                'name' => 'Алексей Покупателев',
                'login' => 'alex_buyer',
                'email' => 'alex@example.com',
                'password' => Hash::make('CustomerAlex123!'),
                'role_id' => 3, // Customer
                'created_at' => $now->subDays(15),
            ],
            
            // 4. Модератор контента
            [
                'name' => 'Ольга Модераторова',
                'login' => 'content_moder',
                'email' => 'moderator@marketplace.ru',
                'password' => Hash::make('ModerOlga789!'),
                'role_id' => 4, // Moderator
                'created_at' => $now->subDays(5),
            ],
            
            // 5. Продавец одежды
            [
                'name' => 'Мария Фэшн',
                'login' => 'fashion_seller',
                'email' => 'fashion@marketplace.ru',
                'password' => Hash::make('FashionMaria123!'),
                'role_id' => 2, // Seller
                'created_at' => $now->subDays(20),
            ],
            
            // 6. Менеджер контента
            [
                'name' => 'Дмитрий Контентов',
                'login' => 'content_manager',
                'email' => 'content@marketplace.ru',
                'password' => Hash::make('ContentDima456!'),
                'role_id' => 5, // Content Manager
                'created_at' => $now->subDays(7),
            ],
            
            // 7. Покупатель (частый клиент)
            [
                'name' => 'Анна Частникова',
                'login' => 'anna_regular',
                'email' => 'anna@example.com',
                'password' => Hash::make('AnnaBuyer789!'),
                'role_id' => 3, // Customer
                'created_at' => $now->subDays(30),
            ],
            
            // 8. Продавец товаров для дома
            [
                'name' => 'Сергей Хозтоваров',
                'login' => 'home_seller',
                'email' => 'homegoods@marketplace.ru',
                'password' => Hash::make('HomeSergey123!'),
                'role_id' => 2, // Seller
                'created_at' => $now->subDays(12),
            ],

            // 9. Продавец товаров для дома
            [
                'name' => 'Виталий морозов',
                'login' => 'sport_seller',
                'email' => 'sportworks@example.ru',
                'password' => Hash::make('HomeVitalya123!'),
                'role_id' => 2, // Seller
                'created_at' => $now->subDays(12),
            ],
            
            // 10. Покупатель (новый клиент)
            [
                'name' => 'Михаил Новый',
                'login' => 'misha_new',
                'email' => 'misha@example.com',
                'password' => Hash::make('MishaNew456!'),
                'role_id' => 3, // Customer
                'created_at' => $now->subDays(2),
            ],
            
            // 11. Второй администратор
            [
                'name' => 'Артем Резервный',
                'login' => 'admin_backup',
                'email' => 'admin2@marketplace.ru',
                'password' => Hash::make('BackupAdmin123!'),
                'role_id' => 1, // Admin
                'created_at' => $now->subDays(3),
            ],
            // 12. Продавец книг
            [
                'name' => 'Олег Книголюбов',
                'login' => 'book_seller',
                'email' => 'books@marketplace.ru',
                'password' => Hash::make('BooksOleg123!'),
                'role_id' => 2, // Seller
                'created_at' => $now->subDays(8),
            ],
            // 13. Продавец автозапчастей
            [
                'name' => 'Антон Автозапчастей',
                'login' => 'auto_seller',
                'email' => 'autoparts@marketplace.ru',
                'password' => Hash::make('AutoAnton456!'),
                'role_id' => 2, // Seller
                'created_at' => $now->subDays(14),
            ],
        ];

        DB::table('users')->insert($users);
    }
}
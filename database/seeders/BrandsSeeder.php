<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            // Смартфоны и планшеты
            ['brand_name' => 'Apple', 'category' => 'Смартфоны'],
            ['brand_name' => 'Samsung', 'category' => 'Смартфоны'],
            ['brand_name' => 'Huawei', 'category' => 'Смартфоны'],
            ['brand_name' => 'Samsung', 'category' => 'Планшеты'],
            ['brand_name' => 'Lenovo', 'category' => 'Планшеты'],
            ['brand_name' => 'Huawei', 'category' => 'Планшеты'],
            ['brand_name' => 'Spigen', 'category' => 'Чехлы'],
            ['brand_name' => 'Ringke', 'category' => 'Чехлы'],
            ['brand_name' => 'Nillkin', 'category' => 'Чехлы'],
            ['brand_name' => 'Belkin', 'category' => 'Аксессуары'],
            ['brand_name' => 'Baseus', 'category' => 'Аксессуары'],
            ['brand_name' => 'UGreen', 'category' => 'Аксессуары'],
            ['brand_name' => 'JBL', 'category' => 'Гарнитура'],
            ['brand_name' => 'Sony', 'category' => 'Гарнитура'],
            ['brand_name' => 'Xiaomi', 'category' => 'Гарнитура'],
            ['brand_name' => 'ArmSuit', 'category' => 'Защитные пленки'],
            ['brand_name' => 'LK', 'category' => 'Защитные пленки'],
            ['brand_name' => 'Mr.Shield', 'category' => 'Защитные пленки'],

            // Компьютеры и ноутбуки
            ['brand_name' => 'Asus', 'category' => 'Ноутбуки'],
            ['brand_name' => 'Acer', 'category' => 'Ноутбуки'],
            ['brand_name' => 'MSI', 'category' => 'Ноутбуки'],
            ['brand_name' => 'Dell', 'category' => 'Мониторы'],
            ['brand_name' => 'LG', 'category' => 'Мониторы'],
            ['brand_name' => 'Samsung', 'category' => 'Мониторы'],
            ['brand_name' => 'HP', 'category' => 'Компьютеры'],
            ['brand_name' => 'Dell', 'category' => 'Компьютеры'],
            ['brand_name' => 'Lenovo', 'category' => 'Компьютеры'],
            ['brand_name' => 'Apple', 'category' => 'Моноблоки'],
            ['brand_name' => 'Microsoft', 'category' => 'Моноблоки'],
            ['brand_name' => 'HP', 'category' => 'Моноблоки'],
            ['brand_name' => 'Intel', 'category' => 'Комплектующие'],
            ['brand_name' => 'AMD', 'category' => 'Комплектующие'],
            ['brand_name' => 'NVIDIA', 'category' => 'Комплектующие'],

            // Добавь другие категории по аналогии
        ];

        DB::table('Brands')->insert($brands);
    }
}

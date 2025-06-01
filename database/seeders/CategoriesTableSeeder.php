<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Очищаем таблицу и сбрасываем автоинкремент
        DB::statement('TRUNCATE TABLE categories RESTART IDENTITY CASCADE');

        // Вставляем ROOT-категории
        $rootCategories = [
            ['category_name' => 'Электроника', 'category_type' => 'ROOT', 'parent_category_id' => null],
            ['category_name' => 'Одежда', 'category_type' => 'ROOT', 'parent_category_id' => null],
            ['category_name' => 'Дом и сад', 'category_type' => 'ROOT', 'parent_category_id' => null],
            ['category_name' => 'Автомобили', 'category_type' => 'ROOT', 'parent_category_id' => null],
            ['category_name' => 'Книги и искусство', 'category_type' => 'ROOT', 'parent_category_id' => null],
            ['category_name' => 'Спорт и отдых', 'category_type' => 'ROOT', 'parent_category_id' => null],
        ];
        DB::table('categories')->insert($rootCategories);

        // Вставляем PARENT-категории
        $this->insertParentCategories();
        
        // Вставляем LEAF-категории
        $this->insertLeafCategories();
    }

    private function insertParentCategories()
    {
        // Подкатегории для "Электроника"
        $electronicsChildren = [
            ['category_name' => 'Смартфоны и планшеты', 'category_type' => 'PARENT'],
            ['category_name' => 'Компьютеры и ноутбуки', 'category_type' => 'PARENT'],
        ];
        $this->insertCategoriesWithParent($electronicsChildren, 'Электроника');

        // Подкатегории для "Одежда"
        $clothingChildren = [
            ['category_name' => 'Мужская одежда', 'category_type' => 'PARENT'],
            ['category_name' => 'Женская одежда', 'category_type' => 'PARENT'],
            ['category_name' => 'Детская одежда', 'category_type' => 'PARENT'],
        ];
        $this->insertCategoriesWithParent($clothingChildren, 'Одежда');

        // Подкатегории для "Дом и сад"
        $homeChildren = [
            ['category_name' => 'Мебель', 'category_type' => 'PARENT'],
            ['category_name' => 'Освещение', 'category_type' => 'PARENT'],
            ['category_name' => 'Декор', 'category_type' => 'PARENT'],
            ['category_name' => 'Садовая техника', 'category_type' => 'PARENT'],
        ];
        $this->insertCategoriesWithParent($homeChildren, 'Дом и сад');

        // Подкатегории для "Автомобили"
        $autoChildren = [
            ['category_name' => 'Автозапчасти', 'category_type' => 'PARENT'],
            ['category_name' => 'Автоаксессуары', 'category_type' => 'PARENT'],
            ['category_name' => 'Автохимия', 'category_type' => 'PARENT'],
            ['category_name' => 'Автоэлектроника', 'category_type' => 'PARENT'],
        ];
        $this->insertCategoriesWithParent($autoChildren, 'Автомобили');
    }

    private function insertLeafCategories()
    {
        // Смартфоны и планшеты
        $smartphonesChildren = [
            ['category_name' => 'Смартфоны', 'category_type' => 'LEAF'],
            ['category_name' => 'Телефоны', 'category_type' => 'LEAF'],
            ['category_name' => 'Держатели', 'category_type' => 'LEAF'],
            ['category_name' => 'Аксесуары', 'category_type' => 'LEAF'],
            ['category_name' => 'Гарнитура', 'category_type' => 'LEAF'],
            ['category_name' => 'Планшеты', 'category_type' => 'LEAF'],
            ['category_name' => 'Защитные плёнки', 'category_type' => 'LEAF'],
            ['category_name' => 'Чехлы', 'category_type' => 'LEAF'],
        ];
        $this->insertCategoriesWithParent($smartphonesChildren, 'Смартфоны и планшеты');

        // Компьютеры и ноутбуки
        $computersChildren = [
            ['category_name' => 'Ноутбуки', 'category_type' => 'LEAF'],
            ['category_name' => 'Мониторы', 'category_type' => 'LEAF'],
            ['category_name' => 'Компьютеры', 'category_type' => 'LEAF'],
            ['category_name' => 'Моноблоки', 'category_type' => 'LEAF'],
            ['category_name' => 'Комплектующие для пк', 'category_type' => 'LEAF'],
        ];
        $this->insertCategoriesWithParent($computersChildren, 'Компьютеры и ноутбуки');

        // Мужская одежда
        $mensClothingChildren = [
            ['category_name' => 'Футболки', 'category_type' => 'LEAF'],
            ['category_name' => 'Джинсы', 'category_type' => 'LEAF'],
            ['category_name' => 'Куртки', 'category_type' => 'LEAF'],
            ['category_name' => 'Рубашки', 'category_type' => 'LEAF'],
            ['category_name' => 'Шорты', 'category_type' => 'LEAF'],
            ['category_name' => 'Офисная одежда', 'category_type' => 'LEAF'],
        ];
        $this->insertCategoriesWithParent($mensClothingChildren, 'Мужская одежда');

        // Женская одежда
        $womensClothingChildren = [
            ['category_name' => 'Платья', 'category_type' => 'LEAF'],
            ['category_name' => 'Блузки', 'category_type' => 'LEAF'],
            ['category_name' => 'Джинсы', 'category_type' => 'LEAF'],
            ['category_name' => 'Халаты', 'category_type' => 'LEAF'],
            ['category_name' => 'Юбки', 'category_type' => 'LEAF'],
            ['category_name' => 'Офисная одежда', 'category_type' => 'LEAF'],
        ];
        $this->insertCategoriesWithParent($womensClothingChildren, 'Женская одежда');

        // Детская одежда
        $kidsClothingChildren = [
            ['category_name' => 'Бельё', 'category_type' => 'LEAF'],
            ['category_name' => 'Комбинезоны', 'category_type' => 'LEAF'],
            ['category_name' => 'Костюмы', 'category_type' => 'LEAF'],
            ['category_name' => 'Обувь', 'category_type' => 'LEAF'],
            ['category_name' => 'Худи', 'category_type' => 'LEAF'],
            ['category_name' => 'Фартуки', 'category_type' => 'LEAF'],
        ];
        $this->insertCategoriesWithParent($kidsClothingChildren, 'Детская одежда');

        // Дом и сад конечные категории
        $this->insertHomeAndGardenLeafCategories();
        
        // Автомобильные конечные категории
        $this->insertAutoLeafCategories();
        
        // Книги и искусство
        $booksChildren = [
            ['category_name' => 'Романы', 'category_type' => 'LEAF'],
            ['category_name' => 'Научная литература', 'category_type' => 'LEAF'],
            ['category_name' => 'Детские книги', 'category_type' => 'LEAF'],
            ['category_name' => 'Комиксы', 'category_type' => 'LEAF'],
            ['category_name' => 'Путеводители', 'category_type' => 'LEAF'],
            ['category_name' => 'Книги по искусству', 'category_type' => 'LEAF'],
            ['category_name' => 'Музыкальные альбомы', 'category_type' => 'LEAF'],
            ['category_name' => 'Кино и театральные сценарии', 'category_type' => 'LEAF'],
            ['category_name' => 'Репродукции картин', 'category_type' => 'LEAF'],
            ['category_name' => 'Фотография и дизайн', 'category_type' => 'LEAF'],
            ['category_name' => 'Каллиграфия и графика', 'category_type' => 'LEAF'],
            ['category_name' => 'Гравюры', 'category_type' => 'LEAF'],
            ['category_name' => 'Архитектурные проекты', 'category_type' => 'LEAF'],
            ['category_name' => 'Книги по философии', 'category_type' => 'LEAF'],
            ['category_name' => 'Книги по истории', 'category_type' => 'LEAF'],
        ];
        $this->insertCategoriesWithParent($booksChildren, 'Книги и искусство');

        // Спорт и отдых
        $sportsChildren = [
            ['category_name' => 'Футбол', 'category_type' => 'LEAF'],
            ['category_name' => 'Баскетбол', 'category_type' => 'LEAF'],
            ['category_name' => 'Бег', 'category_type' => 'LEAF'],
            ['category_name' => 'Йога', 'category_type' => 'LEAF'],
            ['category_name' => 'Гимнастика', 'category_type' => 'LEAF'],
            ['category_name' => 'Теннис', 'category_type' => 'LEAF'],
            ['category_name' => 'Плавание', 'category_type' => 'LEAF'],
            ['category_name' => 'Горные лыжи', 'category_type' => 'LEAF'],
            ['category_name' => 'Скалолазание', 'category_type' => 'LEAF'],
            ['category_name' => 'Туризм', 'category_type' => 'LEAF'],
            ['category_name' => 'Велоспорт', 'category_type' => 'LEAF'],
            ['category_name' => 'Фитнес и тренировки', 'category_type' => 'LEAF'],
            ['category_name' => 'Гольф', 'category_type' => 'LEAF'],
            ['category_name' => 'Бокс', 'category_type' => 'LEAF'],
            ['category_name' => 'Бильярд', 'category_type' => 'LEAF'],
        ];
        $this->insertCategoriesWithParent($sportsChildren, 'Спорт и отдых');
    }

    private function insertHomeAndGardenLeafCategories()
    {
        // Мебель
        $furnitureChildren = [
            ['category_name' => 'Диваны', 'category_type' => 'LEAF'],
            ['category_name' => 'Кресла', 'category_type' => 'LEAF'],
            ['category_name' => 'Столы', 'category_type' => 'LEAF'],
            ['category_name' => 'Шкафы', 'category_type' => 'LEAF'],
            ['category_name' => 'Комоды', 'category_type' => 'LEAF'],
        ];
        $this->insertCategoriesWithParent($furnitureChildren, 'Мебель');

        // Освещение
        $lightingChildren = [
            ['category_name' => 'Люстры', 'category_type' => 'LEAF'],
            ['category_name' => 'Настольные лампы', 'category_type' => 'LEAF'],
            ['category_name' => 'Бра', 'category_type' => 'LEAF'],
            ['category_name' => 'Торшеры', 'category_type' => 'LEAF'],
            ['category_name' => 'Светодиодные ленты', 'category_type' => 'LEAF'],
        ];
        $this->insertCategoriesWithParent($lightingChildren, 'Освещение');

        // Декор
        $decorChildren = [
            ['category_name' => 'Картины', 'category_type' => 'LEAF'],
            ['category_name' => 'Статуэтки', 'category_type' => 'LEAF'],
            ['category_name' => 'Вазы', 'category_type' => 'LEAF'],
            ['category_name' => 'Зеркала', 'category_type' => 'LEAF'],
            ['category_name' => 'Часы', 'category_type' => 'LEAF'],
        ];
        $this->insertCategoriesWithParent($decorChildren, 'Декор');

        // Садовая техника
        $gardenTechChildren = [
            ['category_name' => 'Газонокосилки', 'category_type' => 'LEAF'],
            ['category_name' => 'Триммеры', 'category_type' => 'LEAF'],
            ['category_name' => 'Воздуходувки', 'category_type' => 'LEAF'],
            ['category_name' => 'Культиваторы', 'category_type' => 'LEAF'],
            ['category_name' => 'Садовые насосы', 'category_type' => 'LEAF'],
        ];
        $this->insertCategoriesWithParent($gardenTechChildren, 'Садовая техника');
    }

    private function insertAutoLeafCategories()
    {
        // Автозапчасти
        $autoPartsChildren = [
            ['category_name' => 'Тормозные колодки', 'category_type' => 'LEAF'],
            ['category_name' => 'Фильтры', 'category_type' => 'LEAF'],
            ['category_name' => 'Амортизаторы', 'category_type' => 'LEAF'],
            ['category_name' => 'Свечи зажигания', 'category_type' => 'LEAF'],
            ['category_name' => 'Радиаторы', 'category_type' => 'LEAF'],
        ];
        $this->insertCategoriesWithParent($autoPartsChildren, 'Автозапчасти');

        // Автоаксессуары
        $autoAccessoriesChildren = [
            ['category_name' => 'Коврики', 'category_type' => 'LEAF'],
            ['category_name' => 'Чехлы для сидений', 'category_type' => 'LEAF'],
            ['category_name' => 'Держатели для телефона', 'category_type' => 'LEAF'],
            ['category_name' => 'Органайзеры', 'category_type' => 'LEAF'],
            ['category_name' => 'Зеркала заднего вида', 'category_type' => 'LEAF'],
        ];
        $this->insertCategoriesWithParent($autoAccessoriesChildren, 'Автоаксессуары');

        // Автохимия
        $autoChemistryChildren = [
            ['category_name' => 'Моторное масло', 'category_type' => 'LEAF'],
            ['category_name' => 'Антифриз', 'category_type' => 'LEAF'],
            ['category_name' => 'Очистители карбюратора', 'category_type' => 'LEAF'],
            ['category_name' => 'Полироли', 'category_type' => 'LEAF'],
            ['category_name' => 'Автошампуни', 'category_type' => 'LEAF'],
        ];
        $this->insertCategoriesWithParent($autoChemistryChildren, 'Автохимия');

        // Автоэлектроника
        $autoElectronicsChildren = [
            ['category_name' => 'Парктроники', 'category_type' => 'LEAF'],
            ['category_name' => 'Видеорегистраторы', 'category_type' => 'LEAF'],
            ['category_name' => 'GPS-навигаторы', 'category_type' => 'LEAF'],
            ['category_name' => 'Камеры заднего вида', 'category_type' => 'LEAF'],
            ['category_name' => 'Автомагнитолы', 'category_type' => 'LEAF'],
        ];
        $this->insertCategoriesWithParent($autoElectronicsChildren, 'Автоэлектроника');
    }

    private function insertCategoriesWithParent(array $categories, string $parentName)
    {
        $parentId = DB::table('categories')
            ->where('category_name', $parentName)
            ->value('category_id');

        $categoriesToInsert = array_map(function ($category) use ($parentId) {
            return array_merge($category, ['parent_category_id' => $parentId]);
        }, $categories);

        DB::table('categories')->insert($categoriesToInsert);
    }
}
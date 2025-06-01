<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProductsSeeder extends Seeder
{
    public function run()
    {
        // Полная очистка таблицы с обнулением автоинкремента

        DB::table('products')->truncate();


        // Получаем всех продавцов (role_id = 2)
        $sellers = DB::table('users')
            ->where('role_id', 2)
            ->pluck('id')
            ->toArray();

        // Если нет продавцов - создаем временного
        if (empty($sellers)) {
            $defaultSeller = DB::table('users')->insertGetId([
                'name' => 'Временный Продавец',
                'login' => 'temp_seller',
                'email' => 'temp@example.com',
                'password' => Hash::make('TempPass123!'),
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $sellers = [$defaultSeller];
        }

        // Распределяем продавцов по категориям
        $categorySellers = [
            'Смартфоны' => $this->getSellerByEmail($sellers, 'electronics@marketplace.ru'),
            'Планшеты' => $this->getSellerByEmail($sellers, 'electronics@marketplace.ru'),
            'Чехлы' => $this->getSellerByEmail($sellers, 'electronics@marketplace.ru'),
            'Гарнитура' => $this->getSellerByEmail($sellers, 'electronics@marketplace.ru'),
            'Ноутбуки' => $this->getSellerByEmail($sellers, 'electronics@marketplace.ru'),
            'Мониторы' => $this->getSellerByEmail($sellers, 'electronics@marketplace.ru'),
            'Комплектующие для пк' => $this->getSellerByEmail($sellers, 'electronics@marketplace.ru'),
            'Футболки' => $this->getSellerByEmail($sellers, 'fashion@marketplace.ru'),
            'Джинсы' => $this->getSellerByEmail($sellers, 'fashion@marketplace.ru'),
            'Куртки' => $this->getSellerByEmail($sellers, 'fashion@marketplace.ru'),
            'Платья' => $this->getSellerByEmail($sellers, 'fashion@marketplace.ru'),
            'Блузки' => $this->getSellerByEmail($sellers, 'fashion@marketplace.ru'),
            'Комбинезоны' => $this->getSellerByEmail($sellers, 'fashion@marketplace.ru'),
            'Обувь' => $this->getSellerByEmail($sellers, 'fashion@marketplace.ru'),
            'Диваны' => $this->getSellerByEmail($sellers, 'homegoods@marketplace.ru'),
            'Люстры' => $this->getSellerByEmail($sellers, 'homegoods@marketplace.ru'),
            'Газонокосилки' => $this->getSellerByEmail($sellers, 'homegoods@marketplace.ru'),
            'Тормозные колодки' => $this->getSellerByEmail($sellers, 'autoparts@marketplace.ru'),
            'Видеорегистраторы' => $this->getSellerByEmail($sellers, 'autoparts@marketplace.ru'),
            'Романы' => $this->getSellerByEmail($sellers, 'books@marketplace.ru'),
            'Книги по искусству' => $this->getSellerByEmail($sellers, 'books@marketplace.ru'),
            'Велоспорт' => $this->getSellerByEmail($sellers, 'sportworks@example.ru'),
            'Футбол' => $this->getSellerByEmail($sellers, 'sportworks@example.ru'),
        ];

        // Заполняем категории товаров
        $this->seedSmartphonesAndTablets($categorySellers);
        $this->seedComputersAndLaptops($categorySellers);
        $this->seedMensClothing($categorySellers);
        $this->seedWomensClothing($categorySellers);
        $this->seedKidsClothing($categorySellers);
        $this->seedHomeAndGarden($categorySellers);
        $this->seedAutomotive($categorySellers);
        $this->seedBooksAndArt($categorySellers);
        $this->seedSportsAndOutdoors($categorySellers);
    }

    private function getSellerByEmail($sellers, $email)
    {
        $seller = DB::table('users')
            ->where('email', $email)
            ->value('id');
            
        return $seller ?? $sellers[array_rand($sellers)];
    }

    private function getBrandId($name)
    {
        return DB::table('brands')->where('brand_name', $name)->value('brand_id');
    }

    private function getCategoryId($name)
    {
        return DB::table('categories')->where('category_name', $name)->value('category_id');
    }

    private function seedSmartphonesAndTablets($categorySellers)
    {
        // Смартфоны
        $this->createProducts('Смартфоны', [
            ['brand' => 'Apple', 'price_range' => [60000, 150000], 'models' => [
                'iPhone 13', 'iPhone 14 Pro', 'iPhone SE', 'iPhone 15 Pro Max'
            ]],
            ['brand' => 'Samsung', 'price_range' => [30000, 120000], 'models' => [
                'Galaxy S23', 'Galaxy A54', 'Galaxy Z Flip5', 'Galaxy S23 Ultra'
            ]],
            ['brand' => 'Huawei', 'price_range' => [25000, 90000], 'models' => [
                'P60 Pro', 'Mate 50 Pro', 'Nova 11i', 'P50 Pocket'
            ]]
        ], $categorySellers['Смартфоны']);

        // Планшеты
        $this->createProducts('Планшеты', [
            ['brand' => 'Samsung', 'price_range' => [15000, 80000], 'models' => [
                'Galaxy Tab S9', 'Galaxy Tab A8', 'Galaxy Tab S9 Ultra'
            ]],
            ['brand' => 'Lenovo', 'price_range' => [10000, 50000], 'models' => [
                'Tab P11', 'Tab M10', 'Yoga Tab 13'
            ]],
            ['brand' => 'Huawei', 'price_range' => [12000, 60000], 'models' => [
                'MatePad Pro', 'MediaPad T5', 'MatePad 11'
            ]]
        ], $categorySellers['Планшеты']);

        // Чехлы
        $this->createProducts('Чехлы', [
            ['brand' => 'Spigen', 'price_range' => [1000, 5000], 'models' => [
                'Rugged Armor', 'Ultra Hybrid', 'Liquid Air'
            ]],
            ['brand' => 'Ringke', 'price_range' => [800, 4000], 'models' => [
                'Fusion X', 'Onyx', 'Air-S'
            ]],
            ['brand' => 'Nillkin', 'price_range' => [500, 3000], 'models' => [
                'Frosted Shield', 'CamShield', 'Super Frosted'
            ]]
        ], $categorySellers['Чехлы']);

        // Гарнитура
        $this->createProducts('Гарнитура', [
            ['brand' => 'JBL', 'price_range' => [2000, 25000], 'models' => [
                'Tune 510BT', 'Quantum 100', 'Live 660NC'
            ]],
            ['brand' => 'Sony', 'price_range' => [3000, 40000], 'models' => [
                'WH-1000XM5', 'WF-1000XM4', 'WH-CH720N'
            ]],
            ['brand' => 'Xiaomi', 'price_range' => [1000, 10000], 'models' => [
                'Redmi Buds 4 Pro', 'Mi True Wireless Earbuds', 'Redmi Buds 3 Lite'
            ]]
        ], $categorySellers['Гарнитура']);
    }

    private function seedComputersAndLaptops($categorySellers)
    {
        // Ноутбуки
        $this->createProducts('Ноутбуки', [
            ['brand' => 'Asus', 'price_range' => [40000, 250000], 'models' => [
                'VivoBook 15', 'ROG Zephyrus G14', 'TUF Gaming F15'
            ]],
            ['brand' => 'Acer', 'price_range' => [35000, 200000], 'models' => [
                'Swift 3', 'Nitro 5', 'Predator Helios 300'
            ]],
            ['brand' => 'MSI', 'price_range' => [60000, 300000], 'models' => [
                'Katana GF76', 'Prestige 14', 'Raider GE78'
            ]]
        ], $categorySellers['Ноутбуки']);

        // Мониторы
        $this->createProducts('Мониторы', [
            ['brand' => 'Dell', 'price_range' => [15000, 150000], 'models' => [
                'S2721QS', 'UltraSharp U2723QE', 'Alienware AW3423DW'
            ]],
            ['brand' => 'LG', 'price_range' => [12000, 120000], 'models' => [
                '27GN800-B', 'UltraFine 32UN880', 'OLED Flex 42'
            ]],
            ['brand' => 'Samsung', 'price_range' => [10000, 200000], 'models' => [
                'Odyssey G7', 'ViewFinity S8', 'Odyssey Ark'
            ]]
        ], $categorySellers['Мониторы']);

        // Комплектующие для ПК
        $this->createProducts('Комплектующие для пк', [
            ['brand' => 'Intel', 'price_range' => [5000, 80000], 'models' => [
                'Core i5-13600K', 'Core i9-13900K', 'Arc A750'
            ]],
            ['brand' => 'AMD', 'price_range' => [4000, 70000], 'models' => [
                'Ryzen 5 7600X', 'Ryzen 9 7950X', 'Radeon RX 7900 XT'
            ]],
            ['brand' => 'NVIDIA', 'price_range' => [30000, 200000], 'models' => [
                'RTX 4060 Ti', 'RTX 4080', 'RTX 4090'
            ]]
        ], $categorySellers['Комплектующие для пк']);
    }

    private function seedMensClothing($categorySellers)
    {
        // Футболки
        $this->createProducts('Футболки', [
            ['brand' => 'Nike', 'price_range' => [1500, 5000], 'models' => [
                'Dri-FIT Academy', 'Sportswear Club', 'Jordan Flight'
            ]],
            ['brand' => 'Adidas', 'price_range' => [1200, 4500], 'models' => [
                'Tiro 23', '3-Stripes Tee', 'Essentials Linear'
            ]],
            ['brand' => 'Puma', 'price_range' => [1000, 4000], 'models' => [
                'Classics Logo', 'Sportstyle', 'Team Top'
            ]]
        ], $categorySellers['Футболки']);

        // Джинсы
        $this->createProducts('Джинсы', [
            ['brand' => 'Levi’s', 'price_range' => [3000, 10000], 'models' => [
                '501 Original', '511 Slim', '502 Regular'
            ]],
            ['brand' => 'Wrangler', 'price_range' => [2500, 8000], 'models' => [
                'Regular Fit', 'Slim Fit', 'Relaxed Fit'
            ]],
            ['brand' => 'Diesel', 'price_range' => [5000, 20000], 'models' => [
                'Jogg Jeans', 'Taper Fit', 'Slim Fit'
            ]]
        ], $categorySellers['Джинсы']);

        // Куртки
        $this->createProducts('Куртки', [
            ['brand' => 'The North Face', 'price_range' => [8000, 40000], 'models' => [
                'ThermoBall Eco', 'Denali 2', 'Aconcagua'
            ]],
            ['brand' => 'Columbia', 'price_range' => [7000, 35000], 'models' => [
                'Watertight II', 'Whirlibird', 'Bugaboo'
            ]],
            ['brand' => 'Zara', 'price_range' => [5000, 20000], 'models' => [
                'Basic Parka', 'Quilted Jacket', 'Bomber Jacket'
            ]]
        ], $categorySellers['Куртки']);
    }

    private function seedWomensClothing($categorySellers)
    {
        // Платья
        $this->createProducts('Платья', [
            ['brand' => 'Zara', 'price_range' => [3000, 15000], 'models' => [
                'Midi Dress', 'Wrap Dress', 'Shirt Dress'
            ]],
            ['brand' => 'H&M', 'price_range' => [2000, 10000], 'models' => [
                'Bodycon Dress', 'Maxi Dress', 'Shirt Dress'
            ]],
            ['brand' => 'Mango', 'price_range' => [2500, 12000], 'models' => [
                'Floral Dress', 'Linen Dress', 'Knit Dress'
            ]]
        ], $categorySellers['Платья']);

        // Блузки
        $this->createProducts('Блузки', [
            ['brand' => 'Gucci', 'price_range' => [20000, 80000], 'models' => [
                'Silk Shirt', 'Cotton Blouse', 'Lace Top'
            ]],
            ['brand' => 'Chanel', 'price_range' => [25000, 100000], 'models' => [
                'Classic Blouse', 'Silk Camellia', 'Tweed Top'
            ]],
            ['brand' => 'Dior', 'price_range' => [18000, 90000], 'models' => [
                'Oblique Blouse', 'Jardin Top', 'Silk Shirt'
            ]]
        ], $categorySellers['Блузки']);

        // Джинсы
        $this->createProducts('Джинсы', [
            ['brand' => 'Levi’s', 'price_range' => [3000, 12000], 'models' => [
                '721 High Rise', 'Ribcage', 'Wedgie'
            ]],
            ['brand' => 'Lee', 'price_range' => [2500, 10000], 'models' => [
                'Riders', 'Flex Fit', 'Slim Fit'
            ]],
            ['brand' => 'Calvin Klein', 'price_range' => [4000, 15000], 'models' => [
                'High Waist', 'Slim Straight', 'Boyfriend'
            ]]
        ], $categorySellers['Джинсы']);
    }

    private function seedKidsClothing($categorySellers)
    {
        // Комбинезоны
        $this->createProducts('Комбинезоны', [
            ['brand' => 'Reima', 'price_range' => [2000, 8000], 'models' => [
                'Winter Suit', 'Rain Suit', 'Softshell Overall'
            ]],
            ['brand' => 'Lassie', 'price_range' => [1500, 7000], 'models' => [
                'Play Suit', 'Snowsuit', 'Waterproof Overall'
            ]],
            ['brand' => 'Kivat', 'price_range' => [1800, 7500], 'models' => [
                'Winter Overalls', 'Quilted Suit', 'Spring Overall'
            ]]
        ], $categorySellers['Комбинезоны']);

        // Обувь
        $this->createProducts('Обувь', [
            ['brand' => 'Geox', 'price_range' => [3000, 10000], 'models' => [
                'Jumper', 'Nebula', 'Breathe'
            ]],
            ['brand' => 'Ecco Kids', 'price_range' => [2500, 9000], 'models' => [
                'Soft 7', 'Biom', 'Track'
            ]],
            ['brand' => 'Skechers', 'price_range' => [2000, 8000], 'models' => [
                'Light-up', 'Flex Appeal', 'S-Lights'
            ]]
        ], $categorySellers['Обувь']);
    }

    private function seedHomeAndGarden($categorySellers)
    {
        // Диваны
        $this->createProducts('Диваны', [
            ['brand' => 'IKEA', 'price_range' => [15000, 80000], 'models' => [
                'Kivik', 'Ektorp', 'Söderhamn'
            ]],
            ['brand' => 'Ashley', 'price_range' => [30000, 150000], 'models' => [
                'Chardonnay', 'Lancaster', 'Graham'
            ]],
            ['brand' => 'Herman Miller', 'price_range' => [100000, 500000], 'models' => [
                'Eames Sofa', 'Action Office', 'Aeron Chair'
            ]]
        ], $categorySellers['Диваны']);

        // Люстры
        $this->createProducts('Люстры', [
            ['brand' => 'Louis Poulsen', 'price_range' => [20000, 300000], 'models' => [
                'PH 5', 'Panthella', 'AJ'
            ]],
            ['brand' => 'Artemide', 'price_range' => [15000, 250000], 'models' => [
                'Tolomeo', 'Melampo', 'Nessino'
            ]],
            ['brand' => 'Flos', 'price_range' => [18000, 280000], 'models' => [
                'Arco', 'Parentesi', 'Snoopy'
            ]]
        ], $categorySellers['Люстры']);

        // Газонокосилки
        $this->createProducts('Газонокосилки', [
            ['brand' => 'Bosch', 'price_range' => [10000, 50000], 'models' => [
                'Rotak 34', 'Rotak 40', 'UniversalRotak 43'
            ]],
            ['brand' => 'Husqvarna', 'price_range' => [15000, 100000], 'models' => [
                'LC 347V', 'LC 353V', 'LC 356V'
            ]],
            ['brand' => 'Makita', 'price_range' => [12000, 80000], 'models' => [
                'ELM3711', 'ELM3720', 'PLM4621'
            ]]
        ], $categorySellers['Газонокосилки']);
    }

    private function seedAutomotive($categorySellers)
    {
        // Тормозные колодки
        $this->createProducts('Тормозные колодки', [
            ['brand' => 'Brembo', 'price_range' => [3000, 20000], 'models' => [
                'P85078', 'P06047', 'P83077'
            ]],
            ['brand' => 'Bosch', 'price_range' => [2000, 15000], 'models' => [
                '0986AB4223', '0986AB4224', '0986AB4225'
            ]],
            ['brand' => 'ATE', 'price_range' => [2500, 18000], 'models' => [
                '13.0460-7542.2', '13.0460-7543.2', '13.0460-7544.2'
            ]]
        ], $categorySellers['Тормозные колодки']);

        // Видеорегистраторы
        $this->createProducts('Видеорегистраторы', [
            ['brand' => 'BlackVue', 'price_range' => [10000, 50000], 'models' => [
                'DR750X', 'DR970X', 'DR590X'
            ]],
            ['brand' => '70mai', 'price_range' => [5000, 30000], 'models' => [
                'A800S', 'A500S', 'Omni'
            ]],
            ['brand' => 'Viofo', 'price_range' => [6000, 35000], 'models' => [
                'A229 Pro', 'A139 Pro', 'A119 Mini 2'
            ]]
        ], $categorySellers['Видеорегистраторы']);
    }

    private function seedBooksAndArt($categorySellers)
    {
        // Романы
        $this->createProducts('Романы', [
            ['brand' => 'Penguin', 'price_range' => [500, 3000], 'models' => [
                '1984', 'To Kill a Mockingbird', 'Pride and Prejudice'
            ]],
            ['brand' => 'HarperCollins', 'price_range' => [400, 2500], 'models' => [
                'The Great Gatsby', 'The Hobbit', 'The Alchemist'
            ]],
            ['brand' => 'АСТ', 'price_range' => [300, 2000], 'models' => [
                'Мастер и Маргарита', 'Преступление и наказание', 'Война и мир'
            ]]
        ], $categorySellers['Романы']);

        // Книги по искусству
        $this->createProducts('Книги по искусству', [
            ['brand' => 'Taschen', 'price_range' => [2000, 15000], 'models' => [
                'Basics Art Series', 'Leonardo da Vinci', 'Vincent van Gogh'
            ]],
            ['brand' => 'Phaidon', 'price_range' => [2500, 20000], 'models' => [
                'The Art Book', 'The Photography Book', 'The Design Book'
            ]],
            ['brand' => 'Rizzoli', 'price_range' => [3000, 18000], 'models' => [
                'Fashion: The Definitive History', 'Interior Design Master Class', 'Art Now'
            ]]
        ], $categorySellers['Книги по искусству']);
    }

    private function seedSportsAndOutdoors($categorySellers)
    {
        // Велоспорт
        $this->createProducts('Велоспорт', [
            ['brand' => 'Specialized', 'price_range' => [30000, 500000], 'models' => [
                'Allez', 'Rockhopper', 'Stumpjumper'
            ]],
            ['brand' => 'Trek', 'price_range' => [25000, 450000], 'models' => [
                'Domane', 'Marlin', 'Fuel EX'
            ]],
            ['brand' => 'Cannondale', 'price_range' => [28000, 400000], 'models' => [
                'Synapse', 'Trail', 'SuperSix'
            ]]
        ], $categorySellers['Велоспорт']);

        // Футбол
        $this->createProducts('Футбол', [
            ['brand' => 'Nike', 'price_range' => [2000, 10000], 'models' => [
                'Merlin', 'Flight', 'Premier'
            ]],
            ['brand' => 'Adidas', 'price_range' => [1800, 9000], 'models' => [
                'Conext21', 'Al Rihla', 'UCL Pro'
            ]],
            ['brand' => 'Puma', 'price_range' => [1500, 8000], 'models' => [
                'Orbita', 'Finale', 'Team Final'
            ]]
        ], $categorySellers['Футбол']);
    }

    private function createProducts($category, $brandsData, $sellerId)
    {
        $categoryId = $this->getCategoryId($category);
        
        foreach ($brandsData as $brandData) {
            $brandId = $this->getBrandId($brandData['brand']);
            
            foreach ($brandData['models'] as $model) {
                $price = rand($brandData['price_range'][0], $brandData['price_range'][1]);
                
                DB::table('products')->insert([
                    'name' => $model,
                    'description' => $this->generateDescription($category, $brandData['brand'], $model),
                    'price' => $price / 100,
                    'category_id' => $categoryId,
                    'brand_id' => $brandId,
                    'user_id' => $sellerId,
                    'stock_quantity' => rand(5, 50),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function generateDescription($category, $brand, $model)
    {
        $descriptions = [
            'Смартфоны' => "Смартфон {$model} с экраном {$this->getRandomScreenSize()}. Процессор: {$this->getRandomProcessor()}, память: {$this->getRandomMemory()}. Камера: {$this->getRandomCamera()}. Батарея: {$this->getRandomBattery()} мАч.",
            'Планшеты' => "Планшет {$model} с диагональю {$this->getRandomTabletSize()}. Операционная система: {$this->getRandomOS()}. Память: {$this->getRandomMemory()}. Время работы: до {$this->getRandomBatteryLife()} часов.",
            'Чехлы' => "Защитный чехол для устройств. Материал: {$this->getRandomCaseMaterial()}. Совместимость: {$model}. Защита от ударов и царапин.",
            'Гарнитура' => "Наушники {$model} с {$this->getRandomHeadphoneFeature()}. Тип: {$this->getRandomHeadphoneType()}. Время работы: до {$this->getRandomBatteryLife()} часов. Вес: {$this->getRandomWeight(100, 300)} г.",
            'Ноутбуки' => "Ноутбук {$model} с процессором {$this->getRandomProcessor()}. Экран: {$this->getRandomScreenSize()}, {$this->getRandomResolution()}. Память: {$this->getRandomMemory()}. Видеокарта: {$this->getRandomGPU()}.",
            'Мониторы' => "Монитор {$model} с диагональю {$this->getRandomScreenSize()}. Разрешение: {$this->getRandomResolution()}. Частота обновления: {$this->getRandomRefreshRate()} Гц. Технология матрицы: {$this->getRandomPanelType()}.",
            'Футболки' => "Футболка {$model}. Состав: 100% хлопок. Плотность: {$this->getRandomFabricWeight()} г/м². Доступна в {$this->getRandomColorCount()} цветах.",
            'Джинсы' => "Джинсы {$model}. Состав: {$this->getRandomJeansMaterial()}. Посадка: {$this->getRandomFitType()}. Доступны в {$this->getRandomColorCount()} цветах.",
            'Диваны' => "Диван {$model}. Материал обивки: {$this->getRandomUpholstery()}. Габариты: {$this->getRandomDimensions(150, 250)} см. Цвет: {$this->getRandomColor()}.",
            'Тормозные колодки' => "Тормозные колодки {$model}. Совместимость: {$this->getRandomCarModels()}. Материал: {$this->getRandomBrakeMaterial()}. Ресурс: {$this->getRandomBrakeLife()} км.",
            'Романы' => "Книга '{$model}'. Автор: {$this->getRandomAuthor()}. Год издания: ".rand(2010, 2023).". Страниц: ".rand(200, 600).". Переплет: {$this->getRandomBookBinding()}.",
            'Велоспорт' => "Велосипед {$model}. Тип: {$this->getRandomBikeType()}. Рама: {$this->getRandomFrameMaterial()}. Вес: {$this->getRandomWeight(8, 15)} кг. Количество скоростей: {$this->getRandomGearCount()}."
        ];

        return $descriptions[$category] ?? "{$model} от {$brand}. Качественный товар в категории {$category}.";
    }

    // Вспомогательные методы для генерации случайных характеристик
    private function getRandomScreenSize()
    {
        $sizes = ['5.8"', '6.1"', '6.5"', '6.7"', '6.9"'];
        return $sizes[array_rand($sizes)];
    }

    private function getRandomTabletSize()
    {
        $sizes = ['8"', '9.7"', '10.1"', '10.5"', '11"', '12.9"'];
        return $sizes[array_rand($sizes)];
    }

    private function getRandomProcessor()
    {
        $processors = ['Snapdragon 8 Gen 2', 'Apple A16 Bionic', 'Exynos 2200', 'MediaTek Dimensity 9000', 'Snapdragon 7+ Gen 2'];
        return $processors[array_rand($processors)];
    }

    private function getRandomMemory()
    {
        $rams = [4, 6, 8, 12, 16];
        $storages = [64, 128, 256, 512, 1024];
        return $rams[array_rand($rams)] . ' ГБ RAM, ' . $storages[array_rand($storages)] . ' ГБ ROM';
    }

    private function getRandomCamera()
    {
        $cameras = [
            'тройная 12+12+12 МП',
            'двойная 50+12 МП',
            'одинарная 108 МП',
            'четверная 48+12+8+5 МП'
        ];
        return $cameras[array_rand($cameras)];
    }

    private function getRandomBattery()
    {
        return rand(3500, 6000);
    }

    private function getRandomBatteryLife()
    {
        return rand(8, 30);
    }

    private function getRandomCaseMaterial()
    {
        $materials = ['поликарбонат', 'силикон', 'кожа', 'TPU', 'комбинированный'];
        return $materials[array_rand($materials)];
    }

    private function getRandomHeadphoneFeature()
    {
        $features = ['активным шумоподавлением', 'прозрачным режимом', 'водозащитой IPX4', 'встроенным микрофоном', 'памятью на 8 ГБ'];
        return $features[array_rand($features)];
    }

    private function getRandomHeadphoneType()
    {
        $types = ['накладные', 'внутриканальные', 'полноразмерные', 'беспроводные', 'проводные'];
        return $types[array_rand($types)];
    }

    private function getRandomWeight($min, $max)
    {
        return rand($min, $max);
    }

    private function getRandomGPU()
    {
        $gpus = ['Intel Iris Xe', 'NVIDIA RTX 3050', 'AMD Radeon RX 6600M', 'NVIDIA RTX 4090', 'AMD Radeon 680M'];
        return $gpus[array_rand($gpus)];
    }

    private function getRandomPanelType()
    {
        $panels = ['IPS', 'VA', 'TN', 'OLED', 'Mini-LED'];
        return $panels[array_rand($panels)];
    }

    private function getRandomFabricWeight()
    {
        return rand(120, 220);
    }

    private function getRandomColorCount()
    {
        $counts = [3, 5, 7, 9, 12];
        return $counts[array_rand($counts)];
    }

    private function getRandomJeansMaterial()
    {
        $materials = ['98% хлопок, 2% эластан', '100% хлопок', 'хлопок с полиэстером'];
        return $materials[array_rand($materials)];
    }

    private function getRandomFitType()
    {
        $fits = ['прямая', 'узкая', 'расслабленная', 'зауженная', 'классическая'];
        return $fits[array_rand($fits)];
    }

    private function getRandomUpholstery()
    {
        $upholsteries = ['текстиль', 'экокожа', 'натуральная кожа', 'микрофибра', 'велюр'];
        return $upholsteries[array_rand($upholsteries)];
    }

    private function getRandomDimensions($min, $max)
    {
        $width = rand($min, $max);
        $depth = rand(70, 120);
        $height = rand(70, 100);
        return "{$width}×{$depth}×{$height}";
    }

    private function getRandomColor()
    {
        $colors = ['черный', 'серый', 'бежевый', 'коричневый', 'синий', 'зеленый'];
        return $colors[array_rand($colors)];
    }

    private function getRandomCarModels()
    {
        $models = [
            'Toyota Camry 2012-2017',
            'Volkswagen Passat B7',
            'Hyundai Solaris 2014-2017',
            'Kia Rio 2015-2020'
        ];
        return $models[array_rand($models)];
    }

    private function getRandomBrakeMaterial()
    {
        $materials = ['керамика', 'органический', 'полуметаллический'];
        return $materials[array_rand($materials)];
    }

    private function getRandomBrakeLife()
    {
        return rand(30000, 70000);
    }

    private function getRandomAuthor()
    {
        $authors = [
            'Джордж Оруэлл',
            'Харпер Ли',
            'Джейн Остин',
            'Фрэнсис Скотт Фицджеральд',
            'Михаил Булгаков'
        ];
        return $authors[array_rand($authors)];
    }

    private function getRandomBookBinding()
    {
        $bindings = ['твердый', 'мягкий', 'кожаный', 'подарочный'];
        return $bindings[array_rand($bindings)];
    }

    private function getRandomBikeType()
    {
        $types = ['горный', 'шоссейный', 'городской', 'гибридный', 'туристический'];
        return $types[array_rand($types)];
    }

    private function getRandomFrameMaterial()
    {
        $materials = ['алюминий', 'карбон', 'сталь', 'титан'];
        return $materials[array_rand($materials)];
    }

    private function getRandomGearCount()
    {
        return rand(1, 27);
    }

    private function getRandomOS()
    {
        $oses = ['Android', 'iOS', 'Windows', 'HarmonyOS'];
        return $oses[array_rand($oses)];
    }

    private function getRandomResolution()
    {
        $resolutions = [
            'HD (1280×720)',
            'Full HD (1920×1080)',
            '2K (2560×1440)',
            '4K UHD (3840×2160)',
            '8K UHD (7680×4320)',
            'Retina (2560×1600)',
            'WQHD (3440×1440)'
        ];
        return $resolutions[array_rand($resolutions)];
    }

    private function getRandomRefreshRate()
    {
        $rates = [60, 75, 90, 120, 144, 165, 240, 360];
        return $rates[array_rand($rates)];
    }

    private function getRandomResponseTime()
    {
        $times = ['1 мс', '2 мс', '4 мс', '5 мс', '8 мс'];
        return $times[array_rand($times)];
    }

    private function getRandomDisplayTechnology()
    {
        $techs = [
            'IPS с LED подсветкой',
            'VA с квантовыми точками',
            'OLED',
            'Mini-LED',
            'Nano-IPS',
            'PLS',
            'TN+Film'
        ];
        return $techs[array_rand($techs)];
    }
}
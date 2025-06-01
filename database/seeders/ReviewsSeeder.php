<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ReviewsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ru_RU'); // Русская локализация для реалистичных комментариев

        // Получаем все продукты
        $products = DB::table('products')->get();
        
        // Получаем всех пользователей
        $users = DB::table('users')->pluck('id');

        foreach ($products as $product) {
            // Генерируем случайное количество отзывов от 0 до 10
            $reviewsCount = rand(0, 10);
            
            // Получаем всех пользователей, кроме создателя продукта
            $availableUsers = $users->reject(function ($userId) use ($product) {
                return $userId == $product->user_id;
            })->shuffle(); // Перемешиваем пользователей

            for ($i = 0; $i < $reviewsCount; $i++) {
                // Если нет доступных пользователей, прекращаем генерацию отзывов
                if ($availableUsers->isEmpty()) {
                    break;
                }

                // Берем первого пользователя из перемешанного списка
                $userId = $availableUsers->pop();
                
                // Генерируем реалистичный отзыв
                $comment = $this->generateRealisticComment($product->name, $faker);
                
                // Генерируем рейтинг от 3 до 10 (но 80% отзывов будут 7-10)
                $rating = $this->generateRealisticRating();

                DB::table('reviews')->insert([
                    'product_id' => $product->product_id,
                    'user_id' => $userId,
                    'rating' => $rating,
                    'comment' => $comment,
                    'created_at' => $faker->dateTimeBetween($product->created_at, 'now'),
                ]);
            }
        }
    }

    private function generateRealisticComment($productName, $faker)
    {
        // Шаблоны для реалистичных отзывов с разным тоном в зависимости от рейтинга
        $positiveTemplates = [
            "Купил {$productName} и очень доволен! Качество на высоте, все работает как надо.",
            "{$productName} - просто отличная вещь! Пользуюсь уже неделю, никаких нареканий.",
            "Не ожидал такого качества от {$productName}. Очень приятно удивлен!",
            "{$productName} полностью оправдывает свою цену. Рекомендую к покупке.",
            "Использую {$productName} уже месяц. Пока все отлично, надеюсь, так и будет.",
        ];

        $neutralTemplates = [
            "{$productName} - неплохой вариант за свои деньги. В целом доволен покупкой.",
            "Приобрел {$productName}, пока все нормально. Посмотрим, как поведет себя в долгосрочной перспективе.",
            "{$productName} соответствует описанию, но есть небольшие недочеты.",
            "Нормальный продукт, но есть куда расти. {$productName} мог бы быть и лучше.",
            "Использую {$productName} уже пару недель. Пока нареканий нет, но и восторга тоже.",
        ];

        $negativeTemplates = [
            "{$productName} не оправдал моих ожиданий. Качество оставляет желать лучшего.",
            "Купил {$productName} и разочарован. Не советую.",
            "Ожидал большего от {$productName}. Качество не соответствует цене.",
            "После месяца использования {$productName} появились проблемы.",
            "Не советую покупать {$productName}. Лучше поискать альтернативы.",
        ];

        // Выбираем шаблон в зависимости от рейтинга
        if ($this->lastGeneratedRating >= 8) {
            $comment = $positiveTemplates[array_rand($positiveTemplates)];
        } elseif ($this->lastGeneratedRating >= 5) {
            $comment = $neutralTemplates[array_rand($neutralTemplates)];
        } else {
            $comment = $negativeTemplates[array_rand($negativeTemplates)];
        }

        // Добавляем немного вариативности
        if (rand(1, 100) <= 40) {
            $comment .= ' ' . $faker->sentence();
        }
        
        // 15% chance добавить эмодзи
        if (rand(1, 100) <= 15) {
            $emojis = $this->lastGeneratedRating >= 7 
                ? ['👍', '👌', '❤️', '🔥', '⭐', '💯'] 
                : ['😐', '🤔', '👎', '😞'];
            $comment .= ' ' . $emojis[array_rand($emojis)];
        }

        return $comment;
    }

    private $lastGeneratedRating; // Для согласования комментария с рейтингом

    private function generateRealisticRating()
    {
        // 80% отзывов будут 7-10, 15% - 5-6, 5% - 3-4
        $rand = rand(1, 100);
        
        if ($rand <= 80) {
            $this->lastGeneratedRating = rand(7, 10); // Положительные отзывы
        } elseif ($rand <= 95) {
            $this->lastGeneratedRating = rand(5, 6);  // Нейтральные отзывы
        } else {
            $this->lastGeneratedRating = rand(3, 4);  // Негативные отзывы
        }

        return $this->lastGeneratedRating;
    }
}
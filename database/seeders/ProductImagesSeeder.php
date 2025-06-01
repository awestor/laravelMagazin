<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductImagesSeeder extends Seeder
{
    public function run()
    {

        DB::table('images')->truncate();


        // Путь к временному изображению в storage
        $tempImagePath = 'images/default_image.png';
        
        // Путь к исходному изображению (положите его в storage/app/private/images/)
        $sourceImage = public_path('images/default_product.png');

        // Проверяем существует ли изображение
        
            if (file_exists($sourceImage)) {
                // Копируем из storage в storage через фасад
                Storage::put($tempImagePath, file_get_contents($sourceImage));
            } else {
                if (!Storage::exists($tempImagePath)) {
                    // Создаем пустое изображение если его нет
                    Storage::put($tempImagePath, $this->generateDefaultImage());
                }
            }


        // Получаем все товары
        $products = DB::table('products')->get();

        foreach ($products as $product) {
            DB::table('images')->insert([
                'product_id' => $product->product_id,
                'image_url' => $tempImagePath,
                'image_type' => 'MAIN',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
    private function generateDefaultImage()
        {
            // Генерируем простую серую картинку 300x300
            $image = imagecreatetruecolor(300, 300);
            $gray = imagecolorallocate($image, 200, 200, 200);
            imagefill($image, 0, 0, $gray);
            ob_start();
            imagepng($image);
            $content = ob_get_clean();
            imagedestroy($image);
            return $content;
        }
}
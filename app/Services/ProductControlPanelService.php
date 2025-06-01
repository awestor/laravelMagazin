<?php
namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class ProductControlPanelService
{
    public function getUserProducts()
    {
        return Product::where('user_id', Auth::id())->get();
    }

    public function getSoldProductsLastMonth()
    {
        return Product::where('user_id', auth()->id())
            ->whereHas('orderItems', function ($query) {
                $query->whereHas('order', function ($subQuery) {
                    $subQuery->where('status', 'ordered')
                            ->whereBetween('order_date', [Carbon::now()->subMonth(), Carbon::now()]);
                });
            })->get();
    }

    public function createProduct(array $data)
    {
        $data['user_id'] = Auth::id();

        return Product::create($data);
    }

    public function createProductWithImages(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['user_id'] = Auth::id();
            $product = Product::create($data);

            if (!$product) {
                throw new \Exception("Ошибка создания товара");
            }

            // Сохранение главного изображения в `public/images`
            if (request()->hasFile('main_image')) {
                $file = request()->file('main_image');
                $imageName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images'), $imageName);

                Image::create([
                    'product_id' => $product->product_id,
                    'image_url' => 'images/' . $imageName,
                    'image_type' => 'MAIN',
                ]);
            }

            // Сохранение дополнительных изображений в `public/images`
            if (request()->hasFile('info_images')) {
                foreach (request()->file('info_images') as $infoImage) {
                    $imageName = time() . '_' . $infoImage->getClientOriginalName();
                    $infoImage->move(public_path('images'), $imageName);

                    Image::create([
                        'product_id' => $product->product_id,
                        'image_url' => 'images/' . $imageName,
                        'image_type' => 'INFO',
                    ]);
                }
            }

            return $product;
        });
    }
    
    public function getViewData(string $page): array
    {
        $assets = [
            'styles' => ['css/header.css'],
            'scripts' => [],
        ];

        if ($page === 'productControlPanel') {
            $assets['styles'][] = 'css/pages/productControlPanel.css';
        } elseif ($page === 'addProduct') {
            $assets['styles'][] = 'css/pages/addProduct.css';
        } elseif ($page === 'editProduct') {
            $assets['styles'][] = 'css/pages/editProduct.css';
        }

        return $assets;
    }





    public function updateProduct(Product $product, array $data)
    {
        \Illuminate\Support\Facades\Log::debug('data:', ['data' => $data]);
        return $product->update($data);
    }

    public function deleteProduct(Product $product)
    {
        return $product->delete();
    }

    public function getProductData()
    {
        return [
            'categories' => Category::all(),
            'brands' => Brand::all(),
        ];
    }
}

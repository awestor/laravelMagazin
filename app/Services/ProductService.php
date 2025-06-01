<?php
namespace App\Services;

use App\Models\Product;
use App\Models\Review;
use App\Models\Image;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductService
{
    public function getProductInfo($productId)
    {
        $query = Product::query()
        ->select([
            'products.product_id',
            'products.name',
            'products.description',
            'products.price',
            'products.stock_quantity',
            'brands.brand_name',
            DB::raw('ROUND(COALESCE(AVG(reviews.rating), 0), 2) as avg_review')
        ])
        ->where('products.product_id', $productId)
        ->rightjoin('brands', 'brands.brand_id', '=', 'products.brand_id')
        ->leftJoin('reviews', 'reviews.product_id', '=', 'products.product_id')
        ->groupBy('products.product_id', 'products.name', 'products.price', 'brands.brand_name');
    
        $info = $query->get()->transform(function ($info) {
            return [
                'name' => $info->name,
                'price' => $info->price,
                'brand_name' => $info->brand_name,
                'review' => $info->avg_review,
                'description' => $info->description,
                'stock_quantity' =>$info->stock_quantity,
                'image' => $info->image_url
            ];
        });

        return $info;
    }

    public function getProdReviews($productId, $data)
    {
        $query = Review::query()
        ->where('reviews.product_id', $productId)
        ->paginate(15, ['*'], 'page', (string) $data);

        $info = $query->map(function ($info) {
            return [
                'user_id' => Crypt::encrypt($info->product_id),
                'rating' => $info->rating,
                'comment' => $info->comment,
                'created_at' => Carbon::parse($info->created_at)->format('d.m.Y:H.i'),
            ];
        });
        return $info;
    }

    private $limit = 30;

    public function getProducts($page)
    {
        $oneMonthAgo = Carbon::now()->subMonth();

        // Запрос самых продаваемых товаров
        $topSoldProducts = Product::select([
            'products.product_id',
            'products.name',
            'products.description',
            'products.price',
            'products.stock_quantity',
            'brands.brand_name',
            'images.image_url',
            DB::raw('ROUND(COALESCE(AVG(reviews.rating), 0), 2) as avg_review'),
            DB::raw('SUM(order_items.quantity) as total_sold')
        ])
        ->join('order_items', 'products.product_id', '=', 'order_items.product_id')
        ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
        ->join('brands', 'brands.brand_id', '=', 'products.brand_id')
        ->leftJoin('reviews', 'reviews.product_id', '=', 'products.product_id')
        ->leftJoin('images', function ($join) {
            $join->on('products.product_id', '=', 'images.product_id')
                ->where('images.image_type', '=', 'MAIN');
        })
        ->where('orders.status', 'ordered')
        ->where('orders.order_date', '>=', $oneMonthAgo)
        ->groupBy('products.product_id', 'products.name', 'products.price', 'brands.brand_name', 'images.image_url', 'products.description', 'products.stock_quantity')
        ->orderByDesc('total_sold') // Сортировка по продажам
        ->limit($this->limit)
        ->get();

        // Если товаров меньше лимита, дополнение по рейтингу
        if ($topSoldProducts->count() < $this->limit) {
            $remainingCount = $this->limit - $topSoldProducts->count();

            $topRatedProducts = Product::select([
                'products.product_id',
                'products.name',
                'products.description',
                'products.price',
                'products.stock_quantity',
                'brands.brand_name',
                'images.image_url',
                DB::raw('ROUND(COALESCE(AVG(reviews.rating), 0), 2) as avg_review')
            ])
            ->join('brands', 'brands.brand_id', '=', 'products.brand_id')
            ->leftJoin('reviews', 'reviews.product_id', '=', 'products.product_id')
            ->leftJoin('images', function ($join) {
                $join->on('products.product_id', '=', 'images.product_id')
                    ->where('images.image_type', '=', 'MAIN');
            })
            ->groupBy('products.product_id', 'products.name', 'products.price', 'brands.brand_name', 'images.image_url', 'products.description', 'products.stock_quantity')
            ->orderByDesc('avg_review') // Сортировка по рейтингу
            ->limit($remainingCount)
            ->get();

            $topSoldProducts = $topSoldProducts->concat($topRatedProducts); // Объединение массивов
        }

        // Хеширование product_id перед возвратом
        $topSoldProducts->transform(function ($product) {
            $product->encrypted_id = Crypt::encrypt($product->product_id);
            return $product;
        });

        return $topSoldProducts;
    }

    public function searchProducts($query)
    {
        return Product::select([
                'products.product_id',
                'products.name',
                'products.description',
                'products.price',
                'products.stock_quantity',
                'brands.brand_name',
                'images.image_url',
                DB::raw('ROUND(COALESCE(AVG(reviews.rating), 0), 2) as avg_review')
            ])
            ->join('brands', 'brands.brand_id', '=', 'products.brand_id')
            ->leftJoin('reviews', 'reviews.product_id', '=', 'products.product_id')
            ->leftJoin('images', function ($join) {
                $join->on('products.product_id', '=', 'images.product_id')
                    ->where('images.image_type', '=', 'MAIN');
            })
            ->where('products.name', 'LIKE', "%{$query}%")
            ->groupBy('products.product_id', 'products.name', 'products.price', 'brands.brand_name', 'images.image_url', 'products.description', 'products.stock_quantity')
            ->limit(100) // Ограничение для количества результатов
            ->get()
            ->map(function ($product) {
                $product->encrypted_id = Crypt::encrypt($product->product_id);
                return $product;
            });
    }

    public function getViewData()
    {
        $data = [
            'styles' => [
                'css/header.css',
                'css/pages/product.css',
            ],
            'scripts' => [
                'js/pages/product.js',
            ],
        ];
    
        if (auth()->check()) {
            $data['styles'][] = 'css/pages/modalProduct.css';
            $data['scripts'][] = 'js/pages/products/modalComment.js';
            $data['scripts'][] = 'js/pages/products/authAction.js';
        }
    
        return $data;
    }

    public function getAssets(string $page): array
    {
        $assets = [
            'styles' => ['css/header.css'],
            'scripts' => [],
        ];

        if ($page === 'search') {
            $assets['styles'][] = 'css/pages/search.css';
            $assets['styles'][] = 'css/pages/mainPageProduct.css';
        }

        return $assets;
    }

    public function getProductImages(int $productId): array
    {
        $images = Image::where('product_id', $productId)->get();

        $formattedImages = $images->map(fn($image) => [
            'image_url' => asset($image->image_url), 
            'image_type' => $image->image_type,
        ])->toArray(); 

        if (empty($formattedImages)) {
            $formattedImages[] = [
                'image_url' => asset('images/default_image.png'),
                'image_type' => 'MAIN'
            ];
        }

        return $formattedImages;
    }
}



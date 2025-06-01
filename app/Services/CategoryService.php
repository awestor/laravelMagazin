<?php
namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CategoryService
{

    public function getCategoryByName(string $name): Category
    {
        //$categoryId = $categoryEnum->getId($categoryEnum);
        return null;
        //return Category::findOrFail($categoryId);
    }
    public function getParentCategories($nameRootCategory)
    {
        return Category::where('category_type', 'PARENT')
            ->where('category_hiden', false)
            ->whereIn('parent_category_id', function($query) use ($nameRootCategory) {
                $query->select('category_id')
                    ->from('categories')
                    ->where('category_name', $nameRootCategory);
            })
            ->get();
    }

    public function getFilteredProducts(array $data)
    {
        $cacheKey = "category_{$data['category_name']}_filters_" . md5(json_encode($data));

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $rootCategoryId = DB::table('categories')
            ->where('category_name', $data['category_name'])
            ->value('category_id');

        $categoryIds = DB::select("
            WITH RECURSIVE category_tree AS (
                SELECT category_id, parent_category_id FROM categories WHERE category_id = ?
                UNION ALL
                SELECT c.category_id, c.parent_category_id 
                FROM categories c
                INNER JOIN category_tree ct ON c.parent_category_id = ct.category_id
            )
            SELECT category_id FROM category_tree", [$rootCategoryId]);

        $categoryIds = array_column($categoryIds, 'category_id');

        // Основной запрос с динамическими фильтрами
        $query = Product::query()
        ->select([
            'products.product_id',
            'products.name',
            'products.price',
            'brands.brand_name',
            'images.image_url',
            DB::raw('ROUND(COALESCE(AVG(reviews.rating), 0), 2) as avg_review'),
            DB::raw('COALESCE(MAX(discounts.discount_value), 0) as discount') // Берётся максимальную скидку (если есть)
        ])
        ->join('brands', 'products.brand_id', '=', 'brands.brand_id')
        ->join('images', 'images.product_id', '=', 'products.product_id')
        ->where('images.image_type', '=', 'MAIN')
        ->leftJoin('reviews', 'reviews.product_id', '=', 'products.product_id')
        ->leftJoin('product_discounts', 'products.product_id', '=', 'product_discounts.product_id') 
        ->leftJoin('discounts', function ($join) {
            $join->on('product_discounts.discount_id', '=', 'discounts.discount_id')
                ->where('discounts.is_active', true) // Скидка активна?
                ->where('discounts.start_date', '<=', Carbon::now()) // Скидка уже началась?
                ->where('discounts.end_date', '>=', Carbon::now()); // Скидка ещё действует?
        })
        ->whereIn('products.category_id', $categoryIds)
        ->groupBy('products.product_id', 'products.name', 'products.price', 'brands.brand_name', 'images.image_url');// Группировка для корректного AVG()


        //\Illuminate\Support\Facades\Log::debug('data:', ['data' => $data]);
        
        
        // Доп фильтры здесь ->

        if (isset($data['price_min'])) {
            $query->where('price', '>=', $data['price_min']);
        }
        if (isset($data['price_max'])) {
            $query->where('price', '<=', $data['price_max']);
        }
        if (isset($data['brand'])) {
            $query->where('brand', $data['brand']);
        }
        if (isset($data['rating_min'])) {
            $query->having('avg_review', '>=', floatval($data['rating_min']));
        }
        if (!empty($data['search'])) {
            $query->where('name', 'LIKE', "%{$data['search']}%");
        }
        if (!empty($data['discount'])) {
            $query->having(DB::raw('COALESCE(MAX(discounts.discount_value), 0)'), '>', 0);
        }

            match ($data['sort']) {
                'price_asc' => $query->orderBy('price', 'asc'),
                'price_desc' => $query->orderBy('price', 'desc'),
                'rating_desc' => $query->orderBy('avg_review', 'desc'),
            };

        $query->paginate(20, ['*'], 'page', $data['page']);


        $products = $query->get()->transform(function ($product) {
            $discountPrice = $product->price;

            if (!empty($product->discount)) {
                $discountPrice = $product->price - ($product->price * ($product->discount / 100));
            }

            return [
                'hashed_id' => Crypt::encrypt($product->product_id),
                'name' => $product->name,
                'price' => $product->price,
                'discount_price' => $discountPrice,
                'brand_name' => $product->brand_name,
                'review' => $product->avg_review,
                'image' => $product->image_url
            ];
        });
        //\Illuminate\Support\Facades\Log::debug('query:', ['query' => $query->get()]);

        //\Illuminate\Support\Facades\Log::debug('products:', ['products' => $products]);
        Cache::put($cacheKey, $products, now()->addMinutes(5));


        return $products;
    }

    public function getViewData(string $page)
    {
        $data = [
            'styles' => [
                'css/header.css',
                'css/pages/category.css',
            ],
            'scripts' => [],
        ];
    
        if (!($page === "showCategory")) {
            $data['scripts'][] = 'js/pages/category.js';
        }
    
        return $data;
    }
}

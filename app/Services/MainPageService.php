<?php
namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class MainPageService
{
    public function getRootCategories()
    {
        $categories = Cache::remember('categories', 60, function () {
            return Category::where([
                ['category_type', 'ROOT'],
                ['category_hiden', false]
            ])
            ->select('category_id', 'category_name')
            ->get();
        });
        return $categories;
    }

    public function getParentCategories($rootCategories)
    {
        return Category::where([
            ['category_type', 'PARENT'],
            ['category_hiden', false]
        ])->where('parent_category_id', function($rootCategories) {
            $rootCategories->select('category_id')
                  ->from('categories')
                  ->where('category_type', 'ROOT');})
        ->get();
    }
 
    public function getViewData(string $page)
    {
        $data = [
            'styles' => [
                'css/header.css',
                'css/pages/mainPage.css',
                'css/pages/mainPageProduct.css',
            ],
            'scripts' => [
                'js/pages/mainPageProduct.js',
            ],
        ];
    
        return $data;
    }
}

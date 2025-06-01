<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'category_id';

    protected $fillable = [
        'category_name',
        'category_type',
        'parent_category_id',
        'category_hiden'
    ];

    // Получить PARENT-категории для ROOT
    public function parentCategories()
    {
        return $this->hasMany(Category::class, 'parent_category_id')
            ->where('category_type', 'PARENT');
    }

    // Получить дочерние категории (LEAF) для PARENT
    public function leafCategories()
    {
        return $this->hasMany(Category::class, 'parent_category_id')
            ->where('category_type', 'LEAF');
    }

    // Получить товары для LEAF-категории
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_category_id', 'category_id');
    }

}
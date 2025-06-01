<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products'; // Имя таблицы в БД
    protected $primaryKey = 'product_id'; // Первичный ключ

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'brand_id',
        'stock_quantity',
        'user_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }
    
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    // Связь один ко многим: у продукта может быть несколько изображений
    public function images()
    {
        return $this->hasMany(Image::class, 'product_id');
    }

    // Получаем только главное (`MAIN`) изображение
    public function mainImage()
    {
        return $this->hasOne(Image::class, 'product_id')->where('image_type', 'MAIN');
    }
    
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'product_id', 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id', 'product_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'product_id');
    }
    


}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images';
    protected $primaryKey = 'image_id';

    protected $fillable = [
        'product_id',
        'image_url',
        'image_type'
    ];

    // Связь с `Product` (Каждое изображение принадлежит одному продукту)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDiscount extends Model
{
    use HasFactory;

    protected $table = 'product_discounts';
    protected $primaryKey = 'product_discounts_id';

    protected $fillable = [
        'product_id',
        'discount_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id'); 
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id', 'discount_id'); 
    }
}

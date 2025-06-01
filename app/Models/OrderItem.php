<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';
    protected $primaryKey = 'order_item_id';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price'
    ];

    // Связь с `Order`
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Связь с `Product`
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

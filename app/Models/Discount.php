<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $table = 'discounts'; 
    protected $primaryKey = 'discount_id'; 
    public $incrementing = true; 
    protected $keyType = 'integer';

    protected $fillable = [
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'is_active',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_discounts', 'discount_id', 'product_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_discounts', 'discount_id', 'user_id');
    }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'user_id',
        'order_date',
        'status'
    ];

    // Связь с `OrderItems`
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(user::class, 'user_id', 'id');
    }


    // Получить текущий заказ пользователя
    public static function currentUserOrder()
    {
        return self::where('user_id', Auth::id())->where('status', 'draft')->first();
    }
}

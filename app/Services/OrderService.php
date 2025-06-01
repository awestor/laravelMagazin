<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;


class OrderService
{
    public function getUserOrder()
    {
        return Order::where('user_id', Auth::id())->where('status', 'draft')->first();
    }

    public function getOrderItems(Order $order)
    {
        return $order->items()->with(['product.images' => function ($query) {
            $query->where('image_type', 'MAIN'); // Загружаем только главное изображение
        }])->get();
    }

    public function updateItemQuantity(OrderItem $orderItem, int $newQuantity)
    {
        $product = $orderItem->product;

        if ($newQuantity <= 0) {
            return ['confirmDelete' => true, 'message' => 'Вы уверены, что хотите удалить этот товар из заказа?'];
        }

        if ($newQuantity > $product->stock_quantity) {
            return ['error' => 'Нельзя заказать больше, чем есть в наличии'];
        }

        $orderItem->update(['quantity' => $newQuantity]);

        return ['message' => 'Количество товара обновлено'];
    }

    public function processCheckout(Order $order)
    {
        $orderItems = $this->getOrderItems($order);
        $outOfStockItems = $orderItems->filter(fn($item) => $item->quantity > $item->product->stock_quantity);

        if ($outOfStockItems->isNotEmpty()) {
            return [
                'confirmProceedWithoutOutOfStock' => true,
                'outOfStockItems' => $outOfStockItems->pluck('product.name'),
                'message' => 'Некоторые товары закончились. Хотите продолжить без них?'
            ];
        }

        DB::beginTransaction();

        try {
            foreach ($orderItems as $item) {
                $product = $item->product;

                if ($product->stock_quantity < $item->quantity) {
                    throw new \Exception('Ошибка: недостаточно товара в наличии');
                }

                $product->decrement('stock_quantity', $item->quantity);
            }

            $order->update(['status' => 'ordered', 'order_date' => now()]);

            DB::commit();
            return ['message' => 'Заказ успешно оформлен'];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function addToOrder()
    {
        $userId = Auth::id();
        $productId = Session::get('product_id');

        // Получаем товар
        $product = Product::findOrFail($productId);
        $quantity = 1;

        // Ищем активный заказ
        $order = Order::where('user_id', $userId)->where('status', 'draft')->first();

        // Если нет заказа, создаем новый
        if (!$order) {
            $order = Order::create([
                'user_id' => $userId,
                'status' => 'draft'
            ]);
        }

        // Проверяем, есть ли уже этот товар в заказе
        $orderItem = OrderItem::where('order_id', $order->order_id)
            ->where('product_id', $product->product_id)
            ->first();

        if ($orderItem) {
            // Если товар уже есть, увеличиваем его количество
            $orderItem->increment('quantity', $quantity);
        } else {
            // Если товара нет, создаем новую запись
            OrderItem::create([
                'order_id' => $order->order_id,
                'product_id' => $product->product_id,
                'quantity' => $quantity,
                'price' => $product->price
            ]);
        }

        return ['message' => 'Товар добавлен в заказ'];
    }

    public function updateBatch(array $updatedQuantities)
    {
        foreach ($updatedQuantities as $orderItemId => $quantity) {
            OrderItem::where('id', $orderItemId)->update(['quantity' => $quantity]);
        }
    }

    public function removeFromOrder($orderItemId): array
    {
        $orderItem = OrderItem::findOrFail($orderItemId);
        $orderId = $orderItem->order_id;

        $orderItem->delete();

        if (!OrderItem::where('order_id', $orderId)->exists()) {
            Order::where('order_id', $orderId)->delete();
        }

        return ['message' => 'Товар удалён'];
    }

    public function finalizeOrder($orderId): array
    {
        $order = Order::findOrFail($orderId);
        $order->update([
            'status' => 'ordered',
            'order_date' => now()
        ]);

        return ['message' => 'Заказ оформлен'];
    }

    public function getViewData(string $page)
    {
        $data = [
            'styles' => [
                'css/header.css',
                'css/pages/orderPage.css'
            ],
            'scripts' => [
                'js/pages/orderPage.js',
            ],
        ];
    
        return $data;
    }
}

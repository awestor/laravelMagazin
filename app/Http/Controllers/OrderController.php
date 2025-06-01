<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\OrderService;
use App\Models\OrderItem;
use App\Models\Order;
use App\Http\Requests\UpdateOrderRequest;




class OrderController extends Controller
{
    protected $orderService;
    
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function show()
    {
        $data = $this->orderService->getViewData('show');

        $order = $this->orderService->getUserOrder();
        $orderItems = $order ? $this->orderService->getOrderItems($order) : [];
        \Illuminate\Support\Facades\Log::debug('orderItems:', ['orderItems' => $orderItems]);

        return view('orders.orderView', $data, ['orderItems' => $orderItems]);
    }

    public function checkout()
    {
        $order = $this->orderService->getUserOrder();
        if (!$order) return response()->json(['error' => 'У вас нет товаров в заказе']);

        $response = $this->orderService->processCheckout($order);
        return response()->json($response);
    }

    public function addToOrder()
    {
        $response = $this->orderService->addToOrder();
        return response()->json($response);
    }

    public function removeFromOrder(Request $request, $orderItemId): JsonResponse
    {
        return response()->json($this->orderService->removeFromOrder($orderItemId));
    }

    public function finalizeOrder(Request $request, $orderId): JsonResponse
    {
        return response()->json($this->orderService->finalizeOrder($orderId));
    }

    public function updateBatch(UpdateOrderRequest $request): JsonResponse
    {
        $this->orderService->updateBatch($request->validated());

        return response()->json(['message' => 'Заказ успешно обновлён!']);
    }
}

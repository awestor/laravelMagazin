@extends('page')

@section('content')
<div class="container">
    <h1>Ваш заказ</h1>

    @if(isset($message))
        <p class="alert alert-info">{{ $message }}</p>
    @endif

    @if(count($orderItems) > 0)
        <div class="product-container">
            @foreach ($orderItems as $item)
                <div class="product-card {{ $item->product->stock_quantity == 0 ? 'out-of-stock' : '' }}">
                    <img src="{{ $item->product->images->where('image_type', 'MAIN')->first()->image_url ?? '/default.jpg' }}" 
                         alt="Изображение товара" class="product-image">

                    <div class="product-info">
                        <h2>{{ $item->product->name }}</h2>
                        <p class="price">{{ $item->price }} ₽</p>

                        <div class="quantity-controls">
                            <input type="number" class="quantity-input" 
                                   data-order-item-id="{{ $item->order_item_id }}" 
                                   value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock_quantity }}">
                            <button class="btn btn-danger delete-item-btn" data-order-item-id="{{ $item->order_item_id }}">❌</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <button id="checkout-btn" class="btn btn-success">💳 Оплатить заказ</button>
    @else
        <p>Вы еще не добавили товары в заказ.</p>
    @endif
</div>
@endsection
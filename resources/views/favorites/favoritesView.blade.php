@extends('page')

@section('content')
<div class="container">
    <h1>Ваши избранные товары</h1>

    @if ($favorites->count() > 0)
        <div class="favorites-grid">
        @foreach ($favorites as $favorite)
            <div class="favorite-item" data-product-id="{{ Crypt::encrypt($favorite->product->product_id) }}">
                <div class="favorite-image">
                    <img src="{{ $favorite->product->images->where('image_type', 'MAIN')->first()->image_url ?? '/images/no-image.png' }}" 
                        alt="{{ $favorite->product->name }}">
                    <span class="remove-favorite-btn" data-product-id="{{ Crypt::encrypt($favorite->product->product_id) }}">❤️</span>
                </div>
                <div class="favorite-info">
                    <h3>{{ $favorite->product->name }}</h3>
                    <p class="rating">⭐ {{ $favorite->product->reviews->avg('rating') ?? 'Нет отзывов' }}</p>
                </div>
            </div>
        @endforeach
        </div>
    @else
        <p>У вас пока нет избранных товаров.</p>
    @endif
</div>
@endsection

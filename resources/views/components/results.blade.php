@extends('page')

@section('content')
    <h2>Результаты поиска по запросу "{{ $query }}"</h2>

    @if($products->isEmpty())
        <p>По данному названию "{{ $query }}" ничего не найдено.</p>
    @else
        <div class="product-grid">
            @foreach ($products as $product)
                <div class="product-card">
                    <img src="{{ $product->image_url ?? '/images/no-image.png' }}" alt="{{ $product->name }}">
                    <h3>{{ $product->name }}</h3>
                    <p>Цена: {{ $product->price }} ₽</p>
                    <p>Бренд: {{ $product->brand_name }}</p>
                    <p>Рейтинг: ⭐ {{ $product->avg_review ?? 'Нет отзывов' }}</p>
                    <a href="{{ route('productInfoList', ['id' => $product->encrypted_id]) }}">Подробнее</a>
                </div>
            @endforeach
        </div>
    @endif
@endsection

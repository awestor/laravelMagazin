@extends('page') 

@section('content')
    <div class="main-page">
        <h1>Добро пожаловать в Web-market!</h1>

        {{-- Категории товаров --}}
        <h2>Выберите категорию</h2>
        <div class="categories">
            
            <ul class="categories-list">
                @foreach($menuData as $category)
                    <li>
                        <a href="{{ route('category.showParent', ['name' => $category->category_name]) }}">
                            {{ $category->category_name }}
                        </a>
                    </li>
                @endforeach
                <li></li><li></li>
            </ul>
        </div>

        {{-- Рекомендации --}}
        <div class="recommendations">
            <h2>Рекомендуемые товары</h2>
            <div class="product-list">
                {{-- Здесь можно использовать @foreach для вывода товаров --}}
                @foreach($products ?? [] as $product)
                    <div class="product-card">
                    {{-- <img src="{{ asset('images/' . $product['image']) }}" alt="{{ $product['name'] }}">--}}
                        <h3>{{ $product['name'] }}</h3>
                        <p>{{ $product['price'] }} ₽</p>
                        <a href="{{ route('product', ['id' => $product['id']]) }}">Подробнее</a>
                    </div>
                @endforeach
            </div>
        </div> 
    </div>
@endsection

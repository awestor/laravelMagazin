@extends('page')

@section('content')
<h2>Редактирование товара: {{ $product->name }}</h2>

<form action="{{ route('updateProduct', $product) }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="name">Название товара:</label>
        <input type="text" id="name" name="name" value="{{ $product->name }}" required>
    </div>

    <div class="form-group">
        <label for="description">Описание:</label>
        <textarea id="description" name="description">{{ $product->description }}</textarea>
    </div>

    <div class="form-group">
        <label for="category_id">Категория:</label>
        <select id="category_id" name="category_id">
            @foreach($categories as $category)
                <option value="{{ $category->category_id }}" {{ $product->category_id == $category->category_id ? 'selected' : '' }}>
                    {{ $category->category_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="brand_id">Бренд:</label>
        <select id="brand_id" name="brand_id">
            @foreach($brands as $brand)
                <option value="{{ $brand->brand_id }}" {{ $product->brand_id == $brand->brand_id ? 'selected' : '' }}>
                    {{ $brand->brand_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="price">Цена:</label>
        <input type="number" id="price" name="price" value="{{ $product->price }}" required>
    </div>

    <div class="form-group">
        <label for="stock_quantity">Количество на складе:</label>
        <input type="number" id="stock_quantity" name="stock_quantity" value="{{ $product->stock_quantity }}" required>
    </div>

    <button type="submit" class="btn btn-success">Сохранить изменения</button>
</form>
@endsection

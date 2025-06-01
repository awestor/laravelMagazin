@extends('page')

@section('content')
<div class="content">
    <h2>Добавить новый товар</h2>

    <form action="{{ route('storeProduct') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Название товара:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="description">Описание:</label>
            <textarea id="description" name="description"></textarea>
        </div>

        <div class="form-group">
            <label for="category_id">Категория:</label>
            <select id="category_id" name="category_id">
                @foreach($categories as $category)
                    <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="brand_id">Бренд:</label>
            <select id="brand_id" name="brand_id">
                @foreach($brands as $brand)
                    <option value="{{ $brand->brand_id }}">{{ $brand->brand_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="price">Цена:</label>
            <input type="number" id="price" name="price" required>
        </div>

        <div class="form-group">
            <label for="stock_quantity">Количество на складе:</label>
            <input type="number" id="stock_quantity" name="stock_quantity" required>
        </div>

        <div class="form-group">
            <label for="main_image">Главное изображение:</label>
            <input type="file" id="main_image" name="main_image" accept="image/*">
        </div>

        <div class="form-group">
            <label for="info_images">Дополнительные изображения:</label>
            <input type="file" id="info_images" name="info_images[]" accept="image/*" multiple>
        </div>

        <button type="submit" class="btn btn-success">Добавить товар</button>
    </form>


</div>
@endsection

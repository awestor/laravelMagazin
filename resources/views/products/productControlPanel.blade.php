@extends('page')

@section('content')
<h2>Управление товарами</h2>

<a href="{{ route('addProduct') }}" class="btn btn-add">Добавить товар</a>

<h3>Ваши товары</h3>
<table>
    <tr>
        <th>Название</th>
        <th>Количество</th>
        <th>Цена</th>
        <th>Рейтинг</th>
        <th>Действия</th>
    </tr>
    @foreach($products as $product)
    <tr>
        <td>{{ $product->name }}</td>
        <td>{{ $product->stock_quantity }}</td>
        <td>{{ $product->price }}</td>
        <td>{{ $product->reviews->avg('rating') ?? 'Нет рейтинга' }}</td>
        <td>
            <div class="btn-group">
                <a href="{{ route('editProduct', $product) }}" class="btn btn-warning">Редактировать</a>
                <form action="{{ route('deleteProduct', $product) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Удалить</button>
                </form>
            </div>
        </td>
    </tr>
    @endforeach
</table>

<h3>Проданные товары за последний месяц</h3>
    @if($soldProducts->isEmpty())
        <p style="text-align: center; font-size: 18px; font-weight: bold; color: #777;">Данных не найдено</p>
    @else
        <table>
            <tr>
                <th>Название</th>
                <th>Выручка</th>
            </tr>
            @foreach($soldProducts as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->orderItems->sum('price') }}</td>
            </tr>
            @endforeach
        </table>
    @endif
@endsection

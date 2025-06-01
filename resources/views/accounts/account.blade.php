@extends('page')

@section('content')
<div class="container">
    <div class="account-layout">
        <div class="account-info">
            <h1>Личный кабинет</h1>

            <p><strong>Имя:</strong> <span id="name">{{ Auth::user()->name }}</span></p>
            <p><strong>Логин:</strong> <span id="login">{{ Auth::user()->login }}</span></p>
            <p><strong>Email:</strong> <span id="email">{{ Auth::user()->email }}</span></p>

            <!-- Уведомление -->
            <div id="notification" style="display: none; padding: 10px; background-color: #28a745; color: white; border-radius: 5px; text-align: center;">
                Данные успешно обновлены!
            </div>

            <button id="edit-btn">Редактировать</button>
            <button id="save-btn" style="display: none;">Сохранить</button>
            <button id="cancel-btn" style="display: none;">Отменить</button>

            <hr>

            <!-- Форма смены пароля -->
            <h2>Смена пароля</h2>
            <div id="password-fields">
                <label for="old-password">Старый пароль:</label>
                <input type="password" id="old-password" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                
                <label for="new-password">Новый пароль:</label>
                <input type="password" id="new-password" autocomplete="new-password">
                
                <label for="confirm-password">Повторите новый пароль:</label>
                <input type="password" id="confirm-password" autocomplete="new-password">

                <button id="change-password-btn">Обновить пароль</button>
            </div>
        </div>
                <div class="orders-list">
            <h2>Ваши заказы</h2>
            <p><strong>Общая сумма всех заказов:</strong> {{ number_format($totalSpent, 2) }} ₽</p>

            @if ($orders->count() > 0)
                <ul>
                    @foreach ($orders as $index => $order)
                        <li class="order-item">
                            <strong>Заказ №{{ $index + 1 }} — №{{ $order->id }} ({{ $order->status }})</strong>
                            <p>Дата: {{ $order->created_at->format('d.m.Y') }}</p>
                            <p>Сумма заказа: {{ $order->items->sum('price') }} ₽</p>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>Вы ещё не совершали заказов.</p>
            @endif


        </div>
    </div>
    <div class="buttons">
        @if(auth()->user()->role->role_name === 'Seller')
            <div class="product-list">
                <a href="{{ route('productControlPanel') }}" class="btn btn-primary">Управление товаром</a>
            </div>
        @endif
        <div class="exit">
            <a href="{{ route('logout') }}" class="btn btn-primary">Выйти</a>
        </div>
    </div>
</div>
@endsection
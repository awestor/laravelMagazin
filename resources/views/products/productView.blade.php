@extends('page')

@section('content')
    @include('components.modalComment')

    <div class="productInfo" id="productInfo">
        <div class="gallery-container">
            <div class="thumbnails-container">
                <div class="thumbnails-scroll">
                    <!-- Миниатюры располагаются через JavaScript -->
                </div>
            </div>
            <div class="main-image-container">
                <img id="main-image" src="" alt="Основное изображение">
                <div id="favorite-container">
                    <span id="favorite-btn" class="favorite" data-status="off">❤</span>
                </div>
            </div>
        </div>
        <div class="main-info" id="main-info">
        </div>
    </div>
    @if(auth()->check())
        <button id="addToOrder-btn">Добавить в заказ</button>
    @endif
    <div id="reviews">
        
    </div>
    @if(auth()->check())
        <button id="addComment-btn" class="btn btn-info btn-primary">Оставить комментарий</button>
    @endif
    
@endsection

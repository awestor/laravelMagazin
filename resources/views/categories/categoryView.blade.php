@extends('page')

@section('content')
    {{-- Подключаем фильтры --}}
    @include('categories.filters')
    <input type="hidden" id="name" value="{{ $name }}">
    <div id="productList">
        
    </div>

    <div id="loadMoreTrigger"></div> {{-- Триггер догрузки --}}
@endsection

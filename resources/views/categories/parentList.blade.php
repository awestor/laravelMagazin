@extends('page')

@section('content')
    <h2>Выберите подкатегорию</h2>
    <div id="productList">
        @foreach($parCategory as $parent)
            <div class="product-card">
                <a href="{{ route('categoryView', ['name' => $parent->category_name]) }}">
                    {{ $parent->category_name }}
                </a>
            </div>
        @endforeach
    </div>
@endsection

@extends('page')

@section('content')

<form method="POST" action="{{ route('login') }}">
    @csrf
    <label>Email:</label>
    <input type="email" name="email" required>
    
    <label>Пароль:</label>
    <input type="password" name="password" required>
    
    <button type="submit">Войти</button>
</form>

@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Главная</a></li>
            <li class="breadcrumb-item active" aria-current="page">Редактирование профиля</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card p-4">
                <h3>Редактирование профиля</h3>
                <my-profile-edit user-id="{{$userId}}"></my-profile-edit>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Главная</a></li>
            <li class="breadcrumb-item">
                <a href="{{route('my-projects.index')}}">Мои проекты</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Редактирование</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card cardEditProject">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <h1>Описание проекта</h1>
                <project is-editing-page="{{$isEditingPage}}" 
                        slug="{{$projectSlug}}">
                </project>
            </div>
        </div>
    </div>
</div>
@endsection

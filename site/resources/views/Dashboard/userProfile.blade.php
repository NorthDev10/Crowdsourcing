@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Главная</a></li>
            <li class="breadcrumb-item active" aria-current="page">Профиль пользователя</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card p-4">
                <div class="row">
                    <div class="col-ms-6">
                        <p><b>Имя пользователя</b>: {{$user['name']}}</p>
                        <p><b>E-mail</b>: {{$user['email']}}</p>
                        <p><b>Телефон</b>: {{$user['phone']}}</p>
                        <p><b>Навыки пользователя</b>:</p>
                        <ul>
                            @forelse ($user['skills'] as $skill)
                                <li class="skill badge badge-info">{{ $skill['name'] }}</li>
                            @empty
                                <p>У пользователя нет навыков!</p>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div id="accordion">
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Отзывы от пользователей
                            </button>
                        </h5>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body">
                                @forelse($user['reviews_from_users'] as $review)
                                    <div class="card executor-card">
                                        <div class="card-header">
                                            <div class="row justify-content-between">
                                                <div class="col-xs-12">
                                                    <a href="{{route('project', [
                                                        'type_of_project' => $review['project']['type_project']['slug'],
                                                        'type_category_id' => $review['project']['type_project_id'],
                                                        'project' => $review['project']['slug']
                                                    ])}}">{{$review['project']['project_name']}}</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <p>{{$review['description']}}</p>
                                                </div>
                                            </div>
                                            @if(!empty($review['answer']))
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <b>Пользователь ответил: </b>
                                                    {{$review['answer'][0]['description']}}
                                                </div>
                                            </div>
                                            @endif
                                        </div> 
                                        <div class="card-footer">
                                            Отзыв написал: <a href="{{route('user_profile', [
                                                'user_id' => $review['reviewer']['id'],
                                            ])}}">{{$review['reviewer']['name']}}</a>
                                        </div>
                                    </div>
                                @empty
                                    <p>Пока никто не оставил отзывов!</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

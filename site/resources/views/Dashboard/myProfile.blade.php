@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Главная</a></li>
            <li class="breadcrumb-item active" aria-current="page">Мой профиль</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card p-4">
                <div class="row justify-content-center">
                    <div class="col-ms-6">
                        <p><b>Имя пользователя</b>: {{$user['name']}}</p>
                        <p><b>E-mail</b>: {{$user['email']}}</p>
                        <p><b>Телефон</b>: {{$user['phone']}}</p>
                        <p><b>Мои навыки</b>:</p>
                        <ul>
                            @forelse ($user['skills'] as $skill)
                                <li class="skill badge badge-info">{{ $skill['name'] }}</li>
                            @empty
                                <p>У вас нет навыков! Хотите добавить?</p>
                            @endforelse
                        </ul>
                    </div>
                    <div class="col-ms-6 project-list">
                        <a title="Редактировать профиль" href="{{route('my-profile.edit', ['user_id' => $user['id']])}}">
                            <span class="fa-pencil action"></span>
                        </a>
                    </div>
                </div>
                <div id="accordion">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Ваши отзывы
                            </button>
                        </h5>
                        </div>
                    
                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                @forelse($user['my_reviews'] as $review)
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
                                        </div> 
                                        <div class="card-footer">
                                            Отзыв для: <a href="{{route('user_profile', [
                                                'user_id' => $review['user']['id'],
                                            ])}}">{{$review['user']['name']}}</a>
                                        </div>
                                    </div>
                                @empty
                                    <p>Вы пока никому не оставляли отзывов!</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
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
                                    <div class="card">
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
                                            <div class="row justify-content-between">
                                                <div class="col-xs-6">
                                                    <p>{{$review['description']}}</p>
                                                </div>
                                                @if($review['reviewer_id'])
                                                <div class="col-xs-6">
                                                    <form method="POST" action="{{route('leave_feedback')}}">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="project_id" value="{{$review['project_id']}}">
                                                        <input type="hidden" name="user_id" value="{{$review['reviewer_id']}}">
                                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#reviews-{{$review['reviewer_id']}}">Написать отзыв</button>
                                                        @component('project.modal')
                                                            @slot('id')
                                                                reviews-{{$review['reviewer_id']}}
                                                            @endslot
                                                            @slot('title')
                                                                Напишите свой отзыв
                                                            @endslot
                                                            @slot('body')
                                                                <textarea class="form-control" name="description"></textarea>
                                                            @endslot
                                                            @slot('btnText')
                                                                Оставить отзыв
                                                            @endslot
                                                        @endcomponent
                                                    </form>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    Ваш отзыв: 
                                                @foreach($review['project']['reviews'] as $myReview)
                                                    @if($myReview['reviewer_id'] == $user['id'] &&
                                                        $myReview['user_id'] == $review['reviewer_id'])
                                                        {{$myReview['description']}}
                                                    @endif
                                                @endforeach
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="card-footer">
                                            Отзыв написал: <a href="{{route('user_profile', [
                                                'user_id' => $review['reviewer']['id'],
                                            ])}}">{{$review['reviewer']['name']}}</a>
                                        </div>
                                    </div>
                                @empty
                                    <p>Вам пока никто не оставил отзывов!</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Ваши комментарии к задачам
                            </button>
                        </h5>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                            <div class="card-body">
                                @forelse($user['comments_on_tasks'] as $comment)
                                    <div class="card executor-card">
                                        <div class="card-header">
                                            <div class="row justify-content-between">
                                                <div class="col-xs-12">
                                                    <a href="{{route('project', [
                                                        'type_of_project' => $comment['subtask']['project']['type_project']['slug'],
                                                        'type_category_id' => $comment['subtask']['project']['type_project_id'],
                                                        'project' => $comment['subtask']['project']['slug']
                                                    ])}}">{{$comment['subtask']['project']['project_name']}}</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <p>{{$comment['comment']}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p>Вы пока не оставляли комментарии!</p>
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

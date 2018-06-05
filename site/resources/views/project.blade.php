@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Каталог задач</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('typeOfProject', [
                    'type_of_project' => $project->typeProject->slug,
                    'category_id' => $project->typeProject->id,
                ])}}">{{$project->typeProject->title}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Просмотр проекта</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="card bg-light mb-3">
                <div class="card-header">
                    <h1>
                        {{$project->project_name}}
                        @if($status)
                            <span title="Проект набирает исполнителей" class="badge badge-primary">Открыт</span>
                        @else
                            <span title="Проект завершён" class="badge badge-danger">Закрыт</span>
                        @endif
                    </h1>
                </div>
                <div class="card-header">
                    <strong>Заказчик:</strong> 
                    @auth
                        <a href="{{route('user_profile', [
                            'user_id' => $project->customer->id,
                        ])}}">{{$project->customer->name}}</a>
                    @else
                        {{$project->customer->name}}
                    @endauth | 
                    <strong>Проект создан:</strong> {{$project->created_at}} | 
                    <strong>Конечный срок:</strong> {{$project->deadline}} | 
                    <strong>Тендер завершается:</strong> {{$project->beforeTenderLeft()}}
                </div>
                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-xs-12">
                            <h5 class="card-title">Описание проекта:</h5>
                            <h6 class="card-title"><b>Название компании</b>: {{$project->brand}}</h6>
                            <h6 class="card-title"><b>Сфера деятельности</b>: {{$project->businessActivity->title}}</h6>
                        </div>
                        @if($project->status != 'closed')
                        <div class="col-xs-12">
                            <h2 title="Крайний срок для проекта"><span class="badge badge-light">Осталось {{$project->beforeDeadlineLeft()}} дней</span></h2>
                        </div>
                        @endif
                    </div>
                    <p class="card-text">{{$project->project_description}}</p>
                    <div class="row">
                        <div class="col-xs-12 col-sm-3">
                            <div class="nav flex-sm-column flex-xs-row nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                @foreach($categorySubtasks as $key1 => $subtasks)
                                <a class="nav-link {{$key1==0?'active':''}}" id="v-pills-{{$subtasks->slug}}-tab" data-toggle="pill" href="#v-pills-{{$subtasks->slug}}" role="tab" aria-controls="v-pills-{{$subtasks->slug}}" aria-selected="{{$key1==0?'true':'false'}}">{{$subtasks->title}}</a>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-9">
                            <div class="tab-content" id="v-pills-tabContent">
                                @foreach($categorySubtasks as $key2 => $subtasks)
                                <div class="tab-pane fade {{$key2==0?'show active':''}}" id="v-pills-{{$subtasks->slug}}" role="tabpanel" aria-labelledby="v-pills-{{$subtasks->slug}}-tab">
                                    <div id="accordion">
                                    @foreach($subtasks->tasksByCategory as $key3 => $task)
                                        <div class="card">
                                            <div class="card-header" id="headingOne">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapse-{{$task->id}}" aria-expanded="{{$key3==0?'true':'false'}}" aria-controls="collapseOne">
                                                {{$task->task_name}}
                                                <span title="Количество исполнителей" class="badge badge-secondary">
                                                    {{$task->involved_executors}} из {{$task->number_executors}}
                                                </span>
                                                @if($task->status)
                                                    <span title="Задача выполнена" class="badge badge-success">
                                                        Задача выполнена
                                                    </span>
                                                @endif
                                                </button>
                                            </h5>
                                            </div>
                                        
                                            <div id="collapse-{{$task->id}}" class="collapse {{$key3==0?'show':''}}" aria-labelledby="headingOne" data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-md-6">
                                                            {{$task->description}}
                                                        </div>
                                                        <div class="col-xs-12 col-md-6 d-flex justify-content-end">
                                                            @if(!$task->status)
                                                                @auth
                                                                    @if($project->user_id != Auth::user()->id && $task->project->status == 'opened')
                                                                    <form method="POST" action="{{route('subscribeTask')}}">
                                                                        {{ csrf_field() }}
                                                                        <input type="hidden" name="taskId" value="{{$task->id}}">
                                                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#ApplyNow{{$task->id}}">Подать заявку</button>
                                                                        @component('project.modal')
                                                                            @slot('id')
                                                                                ApplyNow{{$task->id}}
                                                                            @endslot
                                                                            @slot('title')
                                                                                Введите комментарий
                                                                            @endslot
                                                                            @slot('body')
                                                                                <textarea class="form-control" name="comment"></textarea>
                                                                            @endslot
                                                                            @slot('btnText')
                                                                                Подать заявку
                                                                            @endslot
                                                                        @endcomponent
                                                                    </form>
                                                                    @endif
                                                                @else
                                                                    <button type="button" title="Для подачи заявки, необходимо войти в систему" class="btn btn-info" disabled>Подать заявку</button>
                                                                @endauth
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer" title="Необходимые навыки">
                                                    @foreach ($task->necessarySkills as $skill)
                                                        <span class="badge badge-info">{{$skill->name}}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-xs-12">
            @if($project->status == 'closed')
                @if(count($project->reviews) == 0)
                <h3>Оставьте свой отзыв</h3>
                @else
                <h3>Вы оставили отзыв</h3>
                @endif
            @else
            <h3>Исполнители, желающие принять участие в проекте</h3>
            @endif
        </div>
    </div>
    <div class="row justify-content-center">
        @if($project->status == 'closed')
            @if(count($project->reviews) > 0)
            <div class="col-md-12">
                @foreach($project->reviews as $review)
                <div class="card">
                    <div class="card-body">
                        {{$review->description}}
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="col-md-12">
                <form method="POST" action="{{route('leave_feedback')}}">
                    {{ csrf_field() }}
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" name="project_id" value="{{$project->id}}">
                            <textarea class="form-control" name="description" rows="5"></textarea>
                        </div>
                        <div class="card-footer d-flex justify-content-center">
                            <button type="submit" class="btn btn-info">Оставить отзыв</button>
                        </div>
                    </div>
                </form>
            </div>
            @endif
        @else
        <div class="col-xs-12">
            @forelse($project->executors as $executor)
            <div class="card executor-card">
                <div class="card-header">
                    <div class="row justify-content-between">
                        <div class="col-xs-12">
                            @auth
                                <a href="{{route('user_profile', [
                                    'user_id' => $executor->user->id,
                                ])}}">{{$executor->user->name}}</a>
                            @else
                                {{$executor->user->name}}
                            @endauth
                            @if($executor->user_selected)
                            <span title="Выбранный исполнитель на выполнение задачи: {{$executor->subtask->task_name}}" class="badge badge-success">Выбран</span>
                            @endif
                        </div>
                        <div class="col-xs-12">
                            {{$executor->subtask->task_name}}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-6">
                            @if($executor->comment)
                            <p>Комментарий: {{$executor->comment}}</p>
                            @endif
                        </div>
                        <div class="col-xs-6">
                            @auth
                            @if($project->user_id == Auth::user()->id)
                                @if($executor->user_selected == 0)
                                <form method="POST" action="{{route('give_the_task')}}">
                                    {{ csrf_field() }}
                                    {{ method_field('PUT') }}
                                    <input type="hidden" name="user_id" value="{{$executor->user->id}}">
                                    <input type="hidden" name="subtask_id" value="{{$executor->subtask->id}}">
                                    <input type="hidden" name="user_selected" value="1">
                                    <button type="submit" class="btn btn-info">Отдать задание</button>
                                </form>
                                @else
                                <form method="POST" action="{{route('pick_up_task')}}">
                                    {{ csrf_field() }}
                                    {{ method_field('PUT') }}
                                    <input type="hidden" name="user_id" value="{{$executor->user->id}}">
                                    <input type="hidden" name="subtask_id" value="{{$executor->subtask->id}}">
                                    <input type="hidden" name="user_selected" value="0">
                                    <button type="submit" class="btn btn-danger">Отменить задание</button>
                                </form>
                                @endif
                            @endif
                            @endauth
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    Подкатегория: {{$executor->subtask->category->title}}
                </div>
            </div>
            @empty
                <div class="alert alert-primary" role="alert">
                    Пока нет желающих выполнить этот проект
                </div>
            @endforelse
        </div>
        @endif
    </div>
</div>
@endsection

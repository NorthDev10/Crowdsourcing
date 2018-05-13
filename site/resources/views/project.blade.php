@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Главная</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('subtasks')}}">Каталог задач</a>
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
                    <strong>Заказчик:</strong> {{$project->customer->name}} | 
                    <strong>Проект создан:</strong> {{$project->created_at}} | 
                    <strong>Конечный срок:</strong> {{$project->deadline}} | 
                    <strong>Тендер завершается:</strong> {{$project->tender_closing}}
                </div>
                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-xs-12">
                            <h5 class="card-title">Описание проекта:</h5>
                        </div>
                        <div class="col-xs-12">
                            <h2 title="Крайний срок для проекта"><span class="badge badge-light">Осталось {{$project->beforeDeadlineLeft()}} дней</span></h2>
                        </div>
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
                                                            @auth
                                                                <button type="button" class="btn btn-info">Подать заявку</button>
                                                            @else
                                                                <button type="button" title="Для подачи заявки, необходимо войти в систему" class="btn btn-info" disabled>Подать заявку</button>
                                                            @endauth
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
            <h3>Исполнители, желающие принять участие в проекте</h3>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-xs-12">
            @forelse($project->executors as $executor)
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between">
                        <div class="col-xs-12">
                            {{$executor->user->name}}
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
                    Комментарий:
                    <p>{{$executor->comment}}</p>
                </div>
            </div>
            @empty
                <div class="alert alert-primary" role="alert">
                    Пока нет желающих выполнить этот проект
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

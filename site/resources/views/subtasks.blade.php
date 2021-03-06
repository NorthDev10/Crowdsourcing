@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @isset($typeOfProject)
                <li class="breadcrumb-item">
                    <a href="{{route('home')}}">Каталог задач</a>
                </li>
                @if(isset($category))
                <li class="breadcrumb-item">
                    <a href="{{route('typeOfProject', [
                        'type_of_project' => $typeOfProject->slug,
                        'category_id' => $typeOfProject->id,
                    ])}}">{{$typeOfProject->title}}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{$category->title}}</li>
                @else
                <li class="breadcrumb-item">{{$typeOfProject->title}}</li>
                @endif
            @else
                <li class="breadcrumb-item">Каталог задач</li>
            @endisset
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form class="input-group m-2" method="GET" action="{{route('findTasks')}}">
            <input type="text" name="q" class="form-control" value="{{$q or ''}}" placeholder="Введите ключевые слова">
                <span class="input-group-btn m-2">
                    <button class="btn btn-default" type="submit">НАЙТИ</button>
                </span>
            </form>
        </div>
        <div class="col-md-4">
            <aside class="card">
                <div class="card-header">
                    Категории
                </div>
                <div class="card-body">
                    <ul>
                        @include('categories.partials.asideNav', ['categories' => $projectTypeList])
                    </ul>
                </div>
            </aside>
        </div>
        <div class="col-md-8">
            <div class="card">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        </ul>
                    </div>
                @endif
                @if($SubtaskList->count() > 0)
                    @foreach ($SubtaskList as $Subtask)
                    <div class="card bg-light mb-3">
                        <div class="card-header">
                            <a title="Название проекта" href="{{route('project', [
                                'type_of_project' => $Subtask->project->typeProjectName(),
                                'type_category_id' => $Subtask->project->type_project_id,
                                'project' => $Subtask->project->slug
                            ])}}">{{$Subtask->project->project_name}}</a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <h5 class="card-title" title="Задача">
                                        {{$Subtask->task_name}}
                                        <span title="Количество исполнителей" class="badge badge-secondary">
                                        {{$Subtask->involved_executors}} из {{$Subtask->number_executors}}
                                        </span>
                                    </h5>
                                    <p class="card-text" title="Описание задачи">
                                        {{str_limit($Subtask->description, 100)}}
                                    </p>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <div class="row justify-content-end">
                                        <div class="col-xs-12">
                                            <h2 title="Крайний срок для проекта"><span class="badge badge-light">Осталось {{$Subtask->project->beforeDeadlineLeft()}} дней</span></h2>
                                        </div>
                                    </div>
                                    <div class="row justify-content-end">
                                        <div class="col-xs-12">
                                            @if(!$Subtask->status)
                                                @auth
                                                    <form method="POST" action="{{route('subscribeTask')}}">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="taskId" value="{{$Subtask->id}}">
                                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#ApplyNow{{$Subtask->id}}">Подать заявку</button>
                                                        @component('project.modal')
                                                            @slot('id')
                                                                ApplyNow{{$Subtask->id}}
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
                                                @else
                                                    <button type="button" title="Для подачи заявки, необходимо войти в систему" class="btn btn-info" disabled>Подать заявку</button>
                                                @endauth
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer" title="Необходимые навыки">
                            @foreach ($Subtask->necessarySkills as $skill)
                            <span class="badge badge-info">{{$skill->name}}</span>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="alert alert-primary" role="alert">
                        Нет задач по данной категории
                    </div>
                @endif
            </div>
            <div class="Subtask-paginate d-flex justify-content-center margin-h-20">
                {{ $SubtaskList->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

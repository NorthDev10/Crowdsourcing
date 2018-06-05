@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{route('my-projects.index')}}">Мои проекты</a></li>
            <li class="breadcrumb-item active" aria-current="page">Процесс выполнения проекта</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                @if (session('status'))
                    <div class="alert alert-info">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="card-header">
                    <h1>{{$project->project_name}}
                        @if($project->status != 'closed')
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
                    <h5>Процесс выполнения проекта</h5>
                    <table class="table table-hover project-list">
                        <thead>
                            <tr>
                                <th scope="col">Исполнитель</th>
                                <th scope="col">Приступил к выполнению задачи</th>
                                <th scope="col">Участвует в задаче</th>
                                <th scope="col">Длительность выполнения задачи</th>
                                <th scope="col">Статус задачи</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($project->executors as $executor)
                                <tr>
                                    <td>
                                        <a href="{{route('user_profile', [
                                            'user_id' => $executor->user->id,
                                        ])}}">{{$executor->user->name}}</a>
                                    </td>
                                    <td>{{$executor->updated_at}}</td>
                                    <td>{{$executor->subtask->task_name}}</td>
                                    <td>{{$executor->subtask->durationTask($executor->updated_at, $project)}}</td>
                                    <td>
                                        {{$executor->subtask->status($project)}}
                                    </td>
                                </tr>
                            @empty
                                <p>У проекта ещё нет исполнителей!</p>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

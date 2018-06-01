@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Главная</a></li>
            <li class="breadcrumb-item active" aria-current="page">Мои задачи</li>
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
                <div class="card-body">
                    @forelse($mySubtasks as $task)
                    <div class="card executor-card">
                        <div class="card-header">
                            <div class="row justify-content-between">
                                <div class="col-xs-12">
                                    <a href="{{route('project', [
                                        'type_of_project' => $task->project->typeProject->slug,
                                        'type_category_id' => $task->project->type_project_id,
                                        'project' => $task->project->slug
                                    ])}}">{{$task->task_name}}</a>
                                    @if($task->project->status == 'opened')
                                        <span title="Проект набирает исполнителей" class="badge badge-primary">Открыт</span>
                                    @else
                                        <span title="Проект завершён" class="badge badge-danger">Закрыт</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xs-6">
                                    <p>{{$task->description}}</p>
                                </div>
                                <div class="col-xs-6">
                                    <h2 title="Крайний срок для проекта"><span class="badge badge-light">Осталось {{$task->project->beforeDeadlineLeft()}} дней</span></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                        <p>У вас нет задач!</p>
                    @endforelse
                </div>
            </div>
            <div class="Subtask-paginate d-flex justify-content-center margin-h-20">
                {{ $mySubtasks->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Главная</a></li>
            <li class="breadcrumb-item active" aria-current="page">Мои проекты</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex justify-content-end">
                <a class="btn btn-success m-2" href="{{route('my-projects.create')}}">Добавить проект</a>
            </div>
            <div class="card">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                @if($ProjectList->count() > 0)
                <table class="table table-hover project-list">
                    <thead>
                        <tr>
                            <th scope="col">Название проекта</th>
                            <th scope="col">Тип проекта</th>
                            <th scope="col">Подзадачи</th>
                            <th scope="col">Статус</th>
                            <th scope="col">Проект создан</th>
                            <th scope="col">Конечный срок</th>
                            <th scope="col">Действие</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ProjectList as $Project)
                            <tr>
                                <td>
                                    <a href="{{route('project', [
                                        'type_of_project' => $Project->typeProjectName(),
                                        'type_category_id' => $Project->type_project_id,
                                        'project' => $Project->slug
                                    ])}}">{{$Project->project_name}}</a>
                                    @if($Project->status == 'opened')
                                        <span title="Проект набирает исполнителей" class="badge badge-primary">Открыт</span>
                                    @else
                                        <span title="Проект завершён" class="badge badge-danger">Закрыт</span>
                                    @endif
                                </td>
                                <td>{{$Project->typeProject->title}}</td>
                                <td>
                                    @forelse($Project->subtasks as $subtask)
                                    <ul>
                                        <li>
                                            @if($subtask->status)
                                                <span class="fa-check" title="Задача выполнена"></span>
                                            @endif
                                            {{$subtask->task_name}}
                                        </li>
                                    </ul>
                                    @empty
                                        нет подзадач
                                    @endforelse
                                </td>
                                <td>
                                    @if($Project->status())
                                        <form method="POST" action="{{route('my-projects.update', ['my_project' => $Project->slug])}}">
                                            {{ csrf_field() }}
                                            {{ method_field('PUT') }}
                                            <input type="hidden" name="status" value="closed">
                                            <button class="btn btn-link" type="submit">
                                                <span class="calendar-check action" title="Завершить проект"></span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="fa-check" title="Проект завершён"></span>
                                    @endif
                                    <a class="m-2 p-1" href="{{route('my-projects.show', ['my_project' => $Project->slug])}}">
                                        <span class="fa-tasks" title="Процесс выполнения проекта"></span>
                                    </a>
                                </td>
                                <td>{{$Project->created_at}}</td>
                                <td>{{$Project->deadline}}</td>
                                <td>
                                    <a title="Редактировать проект" href="{{route('my-projects.edit', ['my_project' => $Project->slug])}}">
                                        <span class="fa-pencil action"></span>
                                    </a>
                                    <form method="POST" action="{{route('my-projects.destroy', ['my_project' => $Project->slug])}}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button class="btn btn-link" onclick="return confirm('Вы подтверждаете удаление?')" type="submit">
                                                <span class="fa-trash action" title="Удалить проект"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <div class="alert alert-info" role="alert">
                        У Вас пока нет проектов!
                    </div>
                @endif
            </div>
            <div class="project-paginate d-flex justify-content-center">
                {{ $ProjectList->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

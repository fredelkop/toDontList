@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">My To-Don't List</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Task</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Duration</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Points</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <i class="material-icons opacity-10">close</i>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $task->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $task->description }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            @if($task->duration_minutes)
                                                {{ $task->duration_minutes }} minutes
                                            @else
                                                Indefinite
                                            @endif
                                        </p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-success">{{ $task->points }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($task->avoid_until && now()->lt($task->avoid_until))
                                            <span class="badge badge-sm bg-gradient-info">Avoiding</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-danger">Failed</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('tasks.edit', $task) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit task">
                                            Edit
                                        </a>
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-danger font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Remove task">
                                                Remove
                                            </button>
                                        </form>
                                        @if(!$task->avoid_until || now()->gt($task->avoid_until))
                                            <a href="{{ route('excuses.create', ['task' => $task]) }}" class="text-warning font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Make excuse">
                                                Excuse
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-5 col-md-6 mb-4 mb-md-0">
            <a href="{{ route('points.check') }}" class="btn btn-lg bg-gradient-info w-100">
                Check My Procrastination
            </a>
        </div>
        <div class="col-lg-5 col-md-6">
            <a href="{{ route('tasks.create') }}" class="btn btn-lg bg-gradient-primary w-100">
                Add New Task to Avoid
            </a>
        </div>
    </div>
</div>
@endsection

<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\PointLog;
use App\Notifications\TaskStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PointController extends Controller
{
    public function checkTasks()
    {
        $user = Auth::user();
        $tasks = $user->tasks()->where('active', true)->get();
        $results = [];

        foreach ($tasks as $task) {
            if ($task->avoid_until && now()->lt($task->avoid_until)) {
                // Successfully avoided the task
                $points = 1; // Or any logic you want for points
                $task->increment('points', $points);

                PointLog::create([
                    'user_id' => $user->id,
                    'task_id' => $task->id,
                    'points' => $points,
                    'type' => 'earned',
                    'reason' => 'Successfully avoided task for duration'
                ]);

                $results[] = [
                    'task' => $task,
                    'status' => 'success',
                    'message' => "Congratulations! You haven't done '{$task->name}' for {$task->duration_minutes} minutes! +{$points} points!"
                ];
            } else {
                // Failed to avoid the task
                $points = 1; // Or any logic for point deduction
                $task->decrement('points', $points);

                PointLog::create([
                    'user_id' => $user->id,
                    'task_id' => $task->id,
                    'points' => $points,
                    'type' => 'lost',
                    'reason' => 'Failed to avoid task'
                ]);

                $results[] = [
                    'task' => $task,
                    'status' => 'failure',
                    'message' => "Oh no! You were supposed to avoid '{$task->name}' but you did it! -{$points} points!"
                ];
            }
        }

        // In the success part of checkTasks method:
        $user->notify(new TaskStatusNotification($task, true, $points));

        // In the failure part:
        $user->notify(new TaskStatusNotification($task, false, $points));

        return view('points.check', compact('results'));
    }
}

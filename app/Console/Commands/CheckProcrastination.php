<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskStatusNotification;
use Illuminate\Console\Command;

class CheckProcrastination extends Command
{
    protected $signature = 'procrastination:check';
    protected $description = 'Check user procrastination status';

    public function handle()
    {
        $tasks = Task::with('user')
            ->where('active', true)
            ->where('avoid_until', '<=', now())
            ->get();

        foreach ($tasks as $task) {
            $points = 1;
            $task->decrement('points', $points);

            $task->user->notify(new TaskStatusNotification(
                $task,
                false,
                $points
            ));
        }

        $this->info('Checked '.$tasks->count().' tasks for procrastination.');
    }
}

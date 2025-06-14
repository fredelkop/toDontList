<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskStatusNotification extends Notification
{
    use Queueable;

    public $task;
    public $success;
    public $points;

    public function __construct($task, $success, $points)
    {
        $this->task = $task;
        $this->success = $success;
        $this->points = $points;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        if ($this->success) {
            return [
                'title' => 'Procrastination Success!',
                'message' => "Congratulations! You haven't done '{$this->task->name}' for {$this->task->duration_minutes} minutes! +{$this->points} points!",
                'link' => route('tasks.index'),
                'icon' => 'check_circle',
                'color' => 'success',
            ];
        } else {
            return [
                'title' => 'Procrastination Failure!',
                'message' => "Oh no! You were supposed to avoid '{$this->task->name}' but you did it! -{$this->points} points!",
                'link' => route('excuses.create', ['task' => $this->task]),
                'icon' => 'error',
                'color' => 'danger',
            ];
        }
    }
}

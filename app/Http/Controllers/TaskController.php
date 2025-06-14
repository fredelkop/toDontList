<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Auth::user()->tasks()->where('active', true)->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
        ]);

        $task = Auth::user()->tasks()->create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'duration_minutes' => $validated['duration_minutes'],
            'avoid_until' => $validated['duration_minutes']
                ? now()->addMinutes($validated['duration_minutes'])
                : null,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task added to your To-Don\'t list!');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
        ]);

        $task->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'duration_minutes' => $validated['duration_minutes'],
            'avoid_until' => $validated['duration_minutes']
                ? now()->addMinutes($validated['duration_minutes'])
                : null,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated!');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->update(['active' => false]);
        return redirect()->route('tasks.index')->with('success', 'Task removed from your active list!');
    }
}

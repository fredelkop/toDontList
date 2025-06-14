<?php

namespace App\Http\Controllers;

use App\Models\Excuse;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExcuseController extends Controller
{
    public function index()
    {
        $excuses = Auth::user()->excuses()->with('task')->get();
        return view('excuses.index', compact('excuses'));
    }

    public function create(Task $task)
    {
        $this->authorize('view', $task);
        return view('excuses.create', compact('task'));
    }

    public function store(Request $request, Task $task)
    {
        $this->authorize('view', $task);

        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $excuse = Excuse::create([
            'user_id' => Auth::id(),
            'task_id' => $task->id,
            'reason' => $validated['reason'],
        ]);

        return redirect()->route('tasks.index')->with('success', 'Excuse submitted!');
    }

    public function approve(Excuse $excuse)
    {
        $this->authorize('approve', $excuse);

        $excuse->update(['approved' => true]);

        // Restore points if needed
        $excuse->task->increment('points', $excuse->task->points);

        return back()->with('success', 'Excuse approved and points restored!');
    }
}

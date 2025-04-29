<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessComplexTask;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'n' => 'required|integer|min:1'
        ]);

        $task = Task::create(['status' => 'pending']);
        ProcessComplexTask::dispatch($task->id, $request->only('n'));

        return response()->json([
            'task_id' => $task->id,
            'status' => 'pending'
        ]);
    }

    public function status($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        return response()->json([
            'task_id' => $task->id,
            'status' => $task->status
        ]);
    }

    public function result($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        if ($task->status !== 'completed') {
            return response()->json([
                'message' => 'Task not completed yet',
                'status' => $task->status
            ], 202);
        }

        return response()->json([
            'task_id' => $task->id,
            'result' => $task->result
        ]);
    }
}

<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessComplexTask implements ShouldQueue
{
    use Queueable;

    protected $taskId;
    protected $input;

    public function __construct($taskId, $input)
    {
        $this->taskId = $taskId;
        $this->input = $input;
    }

    public function handle()
    {
        $task = Task::find($this->taskId);
        $task->status = 'processing';
        $task->save();

        try {
            // Example: Sum of 1/n^2 for n = 1 to input['n']
            $n = $this->input['n'];
            $sum = 0;
            for ($i = 1; $i <= $n; $i++) {
                $sum += 1 / pow($i, 2);
            }

            $task->status = 'completed';
            $task->result = $sum;
            $task->save();
        } catch (\Exception $e) {
            $task->status = 'failed';
            $task->result = $e->getMessage();
            $task->save();
        }
    }
}
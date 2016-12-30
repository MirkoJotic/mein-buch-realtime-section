<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Task;
use Sentinel;

class TasksController extends Controller
{

    public function getTasks(Request $request)
    {
	$tasks = Task::where('created_by', '!=', Sentinel::getUser()->id)->get();
        return response()->json($tasks);
    }

    public function findTask(Request $request)
    {
        $taskId = $request->get('taskId');
        $task = Task::where(['id'=>$taskId])->first();
        return response()->json($task);
    }

    public function createForm(Request $request)
    {
        return view('centaur.tasks');
    }

    public function create(Request $request)
    {
        $task = new Task();
        $task->title = $request->get('title');
        $task->details = $request->get('details');
        $task->created_by = Sentinel::getUser()->id;
        $task->save();
        return response()->json($task);
    }
}

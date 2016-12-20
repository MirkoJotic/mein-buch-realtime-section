<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Thread;
use App\Task;
use App\User;
use App\Message;
use Sentinel;

class ThreadsController extends Controller
{

    public function findOrCreateThread ( Request $request ) {
        // TODO: add middleware to validate and forbid
        $task_id = $request->get('taskId');
        $task = Task::find($task_id);
        $userTaskCreator = User::find($task->created_by);
        $userThreadInitiator =  Sentinel::getUser();
        $thread = Thread::where(['task_id'=>$task->id])->with('task')->with('messages')->first();
        $taskThreadExists = true;
        if ( $thread === null ) {
            $taskThreadExists = false;
            $thread = new Thread();
            $thread->task_id = $task_id;
            $thread->save();

            $thread->participants()->save($userTaskCreator);
            $thread->participants()->save($userThreadInitiator);
            $message = new Message();
            $message->content = "Hi I would like to chat";
            $message->thread_id = $thread->id;
            $message->user_id = $userThreadInitiator->id;
            $message->save();
            $thread = Thread::where(['id'=>$thread->id])->with('task')->with('messages')->first();
        }
        return response()->json(['thread_exists'=>$taskThreadExists, 'thread'=>$thread]);
    }

    public function saveMessage ( Request $request ) {

        $thread = Thread::find($request->get('thread_id'));
        $content = $request->get('content');
        $user = Sentinel::getUser();
        $message = new Message();
        $message->thread_id = $thread->id;
        $message->content = $content;
        $message->user_id = $user->id;
        $result = $message->save();
	
	$messageWithUser = Message::where(['id'=>$message->id])->with('user')->first();
        event(new \App\Events\NewMessage($messageWithUser, $thread));

        return response()->json($result);
    }

    public function findMineThreads ( Request $request ) {
    // TODO: Protect this 
        $user = Sentinel::getUser();
        $threads = \App\User::where('id', $user->id)->with('threadsWithMessagesAndTask')->first();
//die(json_encode($threads));
        return response()->json($threads);
    }

    public function testMessageWithUser ( Request $request ) {
        $threads = \App\User::where('id', 3)->with('threadsWithMessagesAndTask')->first();
        return response()->json($messageWithUser);
    }


}

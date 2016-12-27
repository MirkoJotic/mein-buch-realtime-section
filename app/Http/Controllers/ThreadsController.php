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

    /* add validation startThreadFromTask */
    public function chatInitiateTask ( Request $request ) {
        // TODO: add middleware to validate and forbid
        $task = Task::where('id'=>$request->get('taskId'))->with('creator');
        $userInitiator =  Sentinel::getUser();

        $thread = Thread::find($thread->id);
        $taskThreadExists = true;
        if ( $thread === null )
        {
            $taskThreadExists = false;
            $thread = new Thread(['task_id'=>$task_id]);
            $thread->save();

            $thread->participants()->attach([$task->creator->id, $userInitiator->id]);
            $message = new Message([
                'thread_id'=>$thread->id,
                'user_id'=>$userInitiator->id,
                'content'=>'Hi I would like to chat'
            ]);
            $message->save();
        }
        $thread = Thread::taskMessagesParticipants($thread->id);
        return response()->json(['thread_exists'=>$taskThreadExists, 'thread'=>$thread]);
    }

    // TODO: protect and validate
    public function chatSendMessage ( Request $request ) {
        $thread = Thread::find($request->get('thread_id'));
        $user = Sentinel::getUser();
        $message = new Message([
            'thread_id'=>$thread->id,
            'user_id'=>$user->id,
            'content'=>$request->get('content')
        ]);
        $message->save();

        $message = Message::messageWithUser($message->id);
        /* Emmit Event */
        event(new \App\Events\NewMessage($message, $thread));

        return response()->json($result);
    }

    // TODO: protect and validate
    public function chatThreads ( Request $request ) {
        $threads = User::threadsTaskUserMessagesUserParticipantsUser(Sentinel::getUser()->id);
        return response()->json($threads);
    }

    // TODO: add validation
    public function chatUsersList ( Request $request ) {
        // Add existent users to array
        $users = User::populateUserList($request->get('s'), [Sentinel::getUser()->id]);
        return response()->json($users);
    }

    public function chatUsersAdd ( Request $request ) {
        // BAN IF ALREADY EXISTS
        $thread = Thread::find($request->get('thread_id'));
        $thread->participants()->attach($request->get('uid'));
        return response()->json(['thread'=>$thread->id, 'user'=>$user]);
    }

}

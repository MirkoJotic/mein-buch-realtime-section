<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Thread;
use App\Task;
use App\User;
use App\Message;
use App\MessageSeenStatus;
use Sentinel;

class ThreadsController extends Controller
{

    /* add validation startThreadFromTask */
    public function chatInitiateTask ( Request $request )
    {
        // TODO: add middleware to validate and forbid
        $task = Task::where('id', $request->get('task_id'))->with('creator')->first();
        $userInitiator =  Sentinel::getUser();

        $thread = Thread::where('task_id', $task->id)->first();
        $taskThreadExists = true;
        if ( $thread === null )
        {
            $taskThreadExists = false;
            $thread = new Thread(['task_id'=>$task->id]);
            $thread->save();

            $thread->participants()->attach([$task->creator->id, $userInitiator->id]);
            $message = new Message([
                'thread_id'=>$thread->id,
                'user_id'=>$userInitiator->id,
                'content'=>'Hi I would like to chat'
            ]);
            $message->save();
            $messageStatus = new MessageSeenStatus(['thread_id'=>$thread->id,'user_id'=>$task->creator->id, 'message_id'=>$message->id]);
            $messageStatus->save();
        }
        $thread = Thread::where('id', $thread->id)->first();
        foreach ($thread->participants as $key => $participant) {
            event(new \App\Events\NewThread($thread, $participant, $taskThreadExists));
        }
        return response()->json(['thread_exists'=>$taskThreadExists, 'thread'=>$thread]);
    }

    // TODO: protect and validate
    public function chatThreads ( Request $request )
    {
        // TODO: we should support filter or something...
        $threads = User::threadsTaskUserMessagesUserParticipantsUser(Sentinel::getUser()->id);
        return response()->json($threads);
    }

    // TODO: protect and validate
    public function chatSendMessage ( Request $request )
    {
        $thread = Thread::with('participants')->find($request->get('thread_id'));
        $user = Sentinel::getUser();
        $message = new Message([
            'thread_id'=>$thread->id,
            'user_id'=>$user->id,
            'content'=>$request->get('content')
        ]);
        $message->save();
        foreach ($thread->participants as $participant) {
            if($user->id !== $participant->id) {
                $messageStatus = new MessageSeenStatus(['thread_id'=>$thread->id,'user_id'=>$participant->id, 'message_id'=>$message->id]);
                $messageStatus->save();
            }
        }
        $message = Message::messageWithUser($message->id);
        /* Emmit Event */
        event(new \App\Events\NewMessage($message, $thread));

        return response()->json($message);
    }

    public function chatMarkMessagesAsRead ( Request $request )
    {
        $messagesStatus = MessageSeenStatus::where('thread_id', $request->get('thread_id'))
                                           ->where('user_id', Sentinel::getUser()->id)
                                           ->get();
        foreach ($messagesStatus as $status) {
            $status->delete();
        }
        return response()->json(true);
    }

    // TODO: add validation
    public function chatUsersList ( Request $request )
    {
        // Add existent users to array
        $users = User::populateUserList($request->get('s'), [Sentinel::getUser()->id]);
        return response()->json($users);
    }

    public function chatUsersAdd ( Request $request )
    {
        // BAN IF ALREADY EXISTS
        $thread = Thread::find($request->get('thread_id'));
        $thread->participants()->attach($request->get('uid'));
        // Emitt thread as event
        event();
        return response()->json(['thread'=>$thread->id, 'user'=>User::find($request->get('uid'))]);
    }

}

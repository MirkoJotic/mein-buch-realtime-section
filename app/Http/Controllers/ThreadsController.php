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
        if ( $thread === null )
        {
            $thread = new Thread(['task_id'=>$task->id]);
            $thread->save();

            $thread->participants()->syncWithoutDetaching([$task->creator->id, $userInitiator->id]);

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
            event(new \App\Events\NewThread($thread, $participant, false));
        }
        return response()->json(['thread_exists'=>$taskThreadExists, 'thread'=>$thread]);
    }

    public function chatInitiatePrivate ( Request $request )
    {
        $currentUser = Sentinel::getUser();
        $otherUser = $request->get('user_id');
        // 1. find all thread with these two users
        $threads = Thread::whereHas('participants', function($q) use ($currentUser) {
                                $q->where('user_id', '=', $currentUser->id);
                            })->whereHas('participants', function($q) use ($otherUser) {
                                $q->where('user_id', '=', $otherUser);
                            })->where('type', 'private')->get();

        // 2. if count is 0 then make a new convo with these to guys
        if ( count($threads) == 0 ) {
            $newThread = new Thread(['type' => 'private']);
            $newThread->save();
            $newThread->participants()->syncWithoutDetaching([$currentUser->id, $otherUser]);
            $thread = Thread::where('id', $newThread->id)->first();
            foreach ($thread->participants as $key => $participant) {
                event(new \App\Events\NewThread($thread, $participant, false));
            }
            // TODO: event();
            return response()->json($newThread);
        }

        // 3. Make sure there is only these two in participants
        foreach ( $threads as $key => $thread ) {
            /* query was supposed to select threads that as participants has
             * currentUser and user with whom we are going to chat if there is more than 2 its
             * a group conversation and we don't want it
             */
            if ($thread->participants->count() > 2) {
                $threads->forget($key);
            }
        }
        // 4. Make sure there is just one of them if true return only one
        if ( count($threads) == 1 )
            return response()->json($threads->first());

        // 5. If more than one there is something wrong and LOG it
        return response()->json("Something went wrong!", 404);

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

        return response()->json(['message'=>$message, 'thread'=>$thread]);
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

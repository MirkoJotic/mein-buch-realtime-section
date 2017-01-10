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

use App\Helpers\ThreadHelper;

class ThreadsController extends Controller
{

    public function chatInitiateTask ( Request $request )
    {
        $this->validate($request, [ 'task_id' => 'required|numeric',]);
        // 1. Find thread by task
        $task = Task::where('id', $request->get('task_id'))->with('creator')->first();
        $currentUser =  Sentinel::getUser();
        $threads = Thread::getTaskThreads($currentUser, $task);

        $thread_count = count($threads);
        // 2. Remove threads with more than 2 participants
        foreach ( $threads as $key => $thread ) {
            if ($thread->participants->count() > 2) {
                $threads->forget($key);
            }
        }
        // 3. If there is one thread left that's the one we need
        if ( $thread_count == 1 )
            return response()->json($threads->first());
        // 4. If there is 0 threads we'll just make a new one
        if ( $thread_count == 0 )
        {
            $helper = new ThreadHelper('task', $currentUser, $task->creator, [], $task);
            $thread = $helper->createNewThread();
            return response()->json($thread);
        }
        // LOG HERE as this is a weird sitch
        return response()->json('Something went wrong. More than 1 thread with 2 same users', 404);

    }

    public function chatInitiatePrivate ( Request $request )
    {
        $this->validate($request, [ 'user_id' => 'required|numeric',]);
        $currentUser = Sentinel::getUser();
        $otherUser = User::find($request->get('user_id'));
        // 1. find all thread with these two users
        $threads = Thread::getPrivateThreads($currentUser, $otherUser);
        $thread_count = count($threads);

        // 3. Make sure there is only these two in participants
        foreach ( $threads as $key => $thread ) {
            if ( count($thread->participants) > 2 ) {
                $threads->forget($key);
            }
        }
        // 4. Make sure there is just one of them if true return only one
        if ( $thread_count == 1 )
            return response()->json($threads->first());

        if ( $thread_count == 0 ) {
            $helper = new ThreadHelper('private', $currentUser, $otherUser);
            $thread = $helper->createNewThread();
            return response()->json($thread);
        }
        // 5. If more than one there is something wrong and LOG it
        return response()->json("Something went wrong! More than 2 threads for 2 users.", 404);

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
        $this->validate($request, ['thread_id' => 'required|numeric','content'=>'required|max:1000']);
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
        // TODO: Look into sending only $message but with('thread')
        /* Emmit Event */
        event(new \App\Events\NewMessage($message, $thread));

        return response()->json(['message'=>$message, 'thread'=>$thread]);
    }

    public function chatMarkMessagesAsRead ( Request $request )
    {
        $messagesStatus = MessageSeenStatus::where('thread_id', $request->get('thread_id'))
                                           ->where('user_id', Sentinel::getUser()->id)
                                           ->delete();
        return response()->json(true);
    }

    public function chatUsersList ( Request $request )
    {
        // Add existent users to array so we can exclude them from drop list
        $users = User::populateUserList($request->get('s'), [Sentinel::getUser()->id]);
        return response()->json($users);
    }

    public function chatUsersAdd ( Request $request )
    {
        $this->validate($request, ['thread_id' => 'required|numeric','uid'=>'required|numeric']);
        $thread = Thread::find($request->get('thread_id'));
        $currentUser = Sentinel::getUser();
        $otherUser = User::find($request->get('uid'));
        // TODO Consult and handle group TASK chat
        if ( count( $thread->participants ) == 2 ) {
            $helper = new ThreadHelper('group', $currentUser, $otherUser, $thread->participants);
            $thread = $helper->createNewThread();
            return response()->json($thread);
        }

        $thread->participants()->syncWithoutDetaching([$otherUser->id]);
        event(new \App\Events\NewThread($thread, $otherUser));
        $thread = Thread::where('id', $thread->id)->first();
        return response()->json($thread);
    }

}

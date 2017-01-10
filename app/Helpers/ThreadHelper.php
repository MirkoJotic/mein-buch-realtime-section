<?php

namespace App\Helpers;

use App\Thread as Thread;
use App\User as User;
use App\Message as Message;
use App\MessageSeenStatus as MessageStatus;
/* To be removed */
use App\Task as Task;

class ThreadHelper
{

    private $type = null;
    private $currentUser = null;
    private $otherUser = null;
    private $task = null;

    private $thread = null;
    private $message = null;
    private $groupParticipants = [];
    private $participants = [];


    /**
     * @type String 'private', 'group', 'negotiation', 'task' | ENUM type in db
     * @currentUser \App\User initiator of action
     * @otherUser \App\User OR null (optional) if new conversation with other user
     * @task \App\Task OR null (optional) if new conversation about task
     */
    public function __construct($type, $currentUser, $otherUser, $groupParticipants = [], $task = null)
    {
        $this->type = $type;
        $this->currentUser = $currentUser;
        $this->otherUser = $otherUser;
        $this->task = $task;
        $this->groupParticipants = $groupParticipants;
    }

    public function createNewThread()
    {
        $task_id = $this->type === 'task' ? $this->task->id : null;
        $thread = new Thread(['type' => $this->type, 'task_id' => $task_id]);
        $thread->save();
        $this->thread = $thread;

        $this->setThreadParticipants();
        $this->addThreadParticipants();
        $this->setFreshThread();
        $this->createNewMessage();
        $this->markMessageAsNotSeen();
        $this->emitNewThreadEvents();
        return $this->thread;
    }

    private function setThreadParticipants()
    {
        foreach ($this->groupParticipants as $participant) {
            array_push($this->participants, $participant->id);
        }
        array_push($this->participants, $this->currentUser->id);
        array_push($this->participants, $this->otherUser->id);
        $this->participants = array_unique($this->participants);
    }

    private function addThreadParticipants()
    {
        $this->thread->participants()->syncWithoutDetaching($this->participants);
    }

    private function setFreshThread()
    {
        $this->thread = Thread::where('id', $this->thread->id)->first();
    }

    public function createNewMessage()
    {
        $txt = ucfirst($this->thread->type)." chat started by ".$this->currentUser->email;
        if ( $this->type === 'task' ) $txt = $txt . ' about "'.$this->task->title.'"';
        $message = new Message([
                'thread_id'=>$this->thread->id,
                'user_id'=>$this->currentUser->id,
                'content'=> $txt
        ]);
        $message->save();
        $this->message = $message;
        return $this->message;
    }

    public function markMessageAsNotSeen()
    {
        foreach ($this->thread->participants as $participant) {
            if ( $this->currentUser->id !== $participant->id ) {
                $status = new MessageStatus(['thread_id'=>$this->thread->id,'user_id'=>$participant->id, 'message_id'=>$this->message->id]);
                $s = $status->save();
            }
        }
        return true;
    }

    public function emitNewThreadEvents()
    {
        foreach ($this->thread->participants as $participant) {
            event(new \App\Events\NewThread($this->thread, $participant));
        }
    }


}

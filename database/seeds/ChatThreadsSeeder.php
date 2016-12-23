<?php

use Illuminate\Database\Seeder;

class ChatThreadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = \App\User::where('id', '!=', 1)
                        ->where('id', '!=', 2)
                        ->get();
        foreach ($users as $key => $user) {
            $otherUsers = \App\User::where('id', '!=', $user->id)
                          ->where('id', '!=', 1)
                          ->where('id', '!=', 2)
                          ->get();
            foreach ($otherUsers as $index => $otherUser) {
                $thread = new \App\Thread();
                $thread->type = 'private';
                $thread->save();
                $thread->participants()->save($user);
                $thread->participants()->save($otherUser);
            }
        }
    }
}

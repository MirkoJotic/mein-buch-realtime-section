<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ChatTasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $users = \App\User::where('id', '!=', 1)
                          ->where('id', '!=', 2)
                          ->get();
        foreach ($users as $key => $user) {
            for ($i=0; $i < 4; $i++) {
                $task = new \App\Task();
                $task->title = $faker->catchPhrase;
                $task->details = $faker->paragraph;
                $task->created_by = $user->id;
                $task->save();
            }
        }
    }
}

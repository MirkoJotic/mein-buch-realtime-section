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
        $user =  \App\User::find(1);

        for($i = 0; $i < 20; $i++) {
            $faker = Faker::create();
            $task = new \App\Task([
              'title'=>$faker->catchPhrase,
              'details'=>$faker->paragraph
            ]);
            $task->created_by = $user->id;
            $task->save();
        }
    }
}

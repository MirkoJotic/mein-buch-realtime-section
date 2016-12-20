<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach(range(1, 5) as $index) {
            DB::table('tasks')->insert([
              'title' => $faker->catchPhrase,
              'details' => $faker->paragraph,
              'created_by' => rand(1, 2)
            ]);
        }
    }
}

<?php

use Illuminate\Database\Seeder;

class ChatUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usersFirstName = array(
            'Mike', 'Hank', 'Pete'
        );
        $usersLastName = array(
            'Vargas', 'Quinlan', 'Menzies'
        );
        for ( $i = 1; $i < 4; $i++) {
            $userEmail = "user$i@user$i.com";
            $password = "kompitenz";
            $user = Sentinel::getUserRepository()->create(array(
                'email'    => $userEmail,
                'password' => $password,
                'first_name' => $usersFirstName[$i-1],
                'last_name' => $usersLastName[$i-1]
            ));

            $code = Activation::create($user)->code;
            Activation::complete($user, $code);

            $administratorRole = Sentinel::findRoleById(1);

            $administratorRole->users()->attach($user);
        }
    }
}

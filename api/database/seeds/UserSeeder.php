<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Activity;

class UserSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        $users = [
            // kevin olson
            [
                'email'=> 'acidjazz@gmail.com',
                'name'=> 'kevin olson',
                'role' => 'admin',
            ],
        ];

        foreach ($users as $user) {
            Activity::log('register', User::create($user), $user);
        }

        factory(App\Models\User::class, 50)->create()->each(function ($user) {
            // dump($user);
        });
    }
}


<?php

use Illuminate\Database\Seeder;
use App\Models\User;

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
        "email"=> "acidjazz@gmail.com",
        "name"=> "kevin olson",
      ],

    ];

    foreach ($users as $user) {
      User::create($user);
      // Bouncer::assign('admin')->to(User::create($user));
    }
  }
}


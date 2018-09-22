<?php

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $plans = [
      [
          'title' => 'Tester',
          'price' => 10.00,
          'views' => 1000,
      ],
      [
          'title' => 'Starter',
          'price' => 100.00,
          'views' => 10000,
      ],
      [
          'title' => 'Growing',
          'price' => 10000.00,
          'views' => 100000,
      ],
      [
          'title' => 'Enterprise',
          'price' => 100000.00,
          'views' => 1000000,
      ],

    ];

    foreach ($plans as $plan) {
      Plan::create($plan);
    }
  }
}


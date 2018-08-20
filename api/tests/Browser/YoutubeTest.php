<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class YoutubeTest extends DuskTestCase
{
  /**
   * A Dusk test example.
   *
   * @return void
   */
  public function testVideo()
  {
    $this->browse(function (Browser $browser) {
      $browser
        ->visit('https://www.youtube.com/watch?v=-LcV8duImEw')
        ->driver->takeScreenshot(__DIR__.'/screenshots/tank1.png');
      $browser
        ->assertSee('Anemone')
        ->waitFor('.ytp-large-play-button')
        ->click('.ytp-large-play-button')
        ->pause(rand(4000,8000))
        ->driver->takeScreenshot(__DIR__.'/screenshots/tank2.png');
    });
  }
}

<?php
/**
 * Short description for index.php
 *
 * @package index
 * @author kevin olson <acidjazz@gmail.com>
 * @version 0.1
 * @copyright (C) 2018 kevin olson <acidjazz@gmail.com>
 * @license APACHE
 */

include 'vendor/autoload.php';

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Chrome\ChromeOptions;


use Campo\UserAgent;


$host = 'http://localhost:4444/wd/hub';
$video = '58sdtOMd5gA';

$options = (new ChromeOptions)->addArguments([
    '--disable-gpu',
    '--headless',
    '--kiosk',
    '--user-agent='.UserAgent::random(),
]);

$driver = RemoteWebDriver::create(
    $host, DesiredCapabilities::chrome()->setCapability(
    ChromeOptions::CAPABILITY, $options
    )
);

$driver->navigate()->to('https://www.youtube.com/watch?v='.$video);
$driver->findElement(WebDriverBy::className('ytp-play-button'))->click();
sleep(10);

// <div class="ytp-icon ytp-icon-large-play-button-hover"></div>
$driver->takeScreenshot('ss-'.time().'.png');
$driver->quit();

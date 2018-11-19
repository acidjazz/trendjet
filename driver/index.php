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
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Chrome\ChromeOptions;

use Campo\UserAgent;

use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$options = (new ChromeOptions)->addArguments([
    '--disable-gpu',
    '--headless',
    '--kiosk',
    '--user-agent='.UserAgent::random(),
]);

$driver = RemoteWebDriver::create(
    'http://localhost:4444/wd/hub', DesiredCapabilities::chrome()->setCapability(
    ChromeOptions::CAPABILITY, $options
    )
);

$boosts = json_decode(file_get_contents('/tmp/boosts.json'), true);


foreach ($boosts as $boost) {

    echo "[DRIVER] BOOST #".$boost['id']."\n";

    $driver->navigate()->to('https://www.youtube.com/watch?v='.$boost['video']['id']);
    $element = WebDriverBy::className('ytp-large-play-button');
    $driver->wait()->until(WebDriverExpectedCondition::titleIs($boost['video']['title'].' - YouTube'));
    $driver->findElement($element)->click();

    $duration = rand(5,10);
    sleep($duration);

    $file = 'shot:'.$boost['video']['id'].':'.$boost['id'].':'.time().'.jpg';

    $driver->takeScreenshot($file);
    exec("convert  -quality 1% -resize 20% $file $file");

    exec("aws s3 cp $file s3://trendjet-shots/ --grants read=uri=http://acs.amazonaws.com/groups/global/AllUsers");

    $client = new Client(['base_uri' => $_ENV['APP_URL']]);
    $query = ['apikey' => $_ENV['API_KEY'], 'json' => 'true'];
    try {
        $response = $client->put('/boost/'.$boost['id'], ['query' => $query]);
    } catch (RequestException $e) {
        echo Psr7\str($e->getRequest());
        if ($e->hasResponse()) {
            echo Psr7\str($e->getResponse());
        }
    }

    $query['file'] = $file;
    $query['duration'] = $duration;
    try {
        $response = $client->post('/shot', ['query' => $query]);
    } catch (RequestException $e) {
        echo Psr7\str($e->getRequest());
        if ($e->hasResponse()) {
            echo Psr7\str($e->getResponse());
        }
    }

}

$driver->quit();

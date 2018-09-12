<?php

namespace App\Services;
/**
 * Wrapper for the YouTube API and url parsing
 *
 * @package YouTubeService
 * @author kevin olson <acidjazz@gmail.com>
 * @version 0.1
 * @copyright (C) 2018 kevin olson <acidjazz@gmail.com>
 * @license APACHE
 */

use Google_Client;
use Google_Service_YouTube;
use Goutte\Client;

 class YouTubeService {

   /**
    * Google Client
    */
   private $client;

   /**
    * Google_Services_YouTube
    */
   private $youtube;

   public function __construct()
   {
      $this->client = new Google_Client();
      $this->client->setApplicationName("Youtube_API_Tests");
      $this->client->setDeveloperKey(config('services.google.api_key'));
      $this->youtube = new Google_Service_YouTube($this->client);
   }

    /**
     * parse different urls and determine types and ids
     *
     * @param String $url
     * @return Array
     */
    public function parse($url)
    {

        $type = 'unknown';
        $id = false;

        $parsed = parse_url($url);

        // https://www.youtube.com/channel/UC6MFZAOHXlKK1FI7V0XQVeA
        if (strpos($parsed['path'], '/channel') === 0)
        {
            $type = 'channel';
            $id = explode('/', $parsed['path'])[2];
        }

        // https://www.youtube.com/user/ProZD
        if (strpos($parsed['path'], '/user') === 0)
        {
            $type = 'channel';
            $id = $this->getChannelId(explode('/', $parsed['path'])[2]);
        }

        // https://www.youtube.com/watch?v=4ZK8Z8hulFg
        if (strpos($parsed['path'], '/watch') === 0)
        {
            $type = 'video';
            $id = explode('=', $parsed['query'])[1];
        }

        // https://youtu.be/aJX4ytfqw6k
        if ($parsed['host'] ===  'youtu.be')
        {
            $type = 'video';
            $id = substr($parsed['path'], 1);
        }

        return ['type' => $type, 'id' => $id];
    }
    /**
     * Get a channel ID from a user
     *
     * @param String $user
     * @return String
     */
    private function getChannelId($user)
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://www.youtube.com/user/'.$user);
        $url = $crawler->filter('link[rel="canonical"]')->attr('href');
        return explode('/', $url)[4];
    }


    /**
     * Get a YouTube video
     *
     * @param String $id
     * @return Object
     */
    public function getVideo($id)
    {

      $video = $this->youtube->videos->listVideos(
        ['statistics,snippet'],
        ['id' => $id, 'maxResults' => 1]
      );

      return [
        'title' => $video->items[0]->snippet->title,
        'views' => $video->items[0]->statistics->viewCount,
      ];

    }

 }



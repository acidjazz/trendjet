<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Models\Video;
use acidjazz\tubestuff\TubeStuff;

class YouTubeController extends Controller
{
    /**
     * Parse a YouTube URL
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function parse(Request $request)
    {

        $this->option('string', 'required|string');

        if (!$this->verify()) {
            return $this->error();
        }

        $result = (new TubeStuff)->parse($request->string);

        if (!$result) {
          return $this->error('Invalid URL');
        }

        return $this->render($result);
    }

    /**
     * Grab channel info and video list from ID
     *
     * @param String $id
     * @param \Illuminate\http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function channel(String $id, Request $request)
    {
        $this->option('pageToken', 'string|nullable');
        $channel = (new TubeStuff(config('services.google.api_key')))
            ->getChannelVideos($id, $request->pageToken);
        $added = Video::whereIn('id', array_keys($channel['videos']))
            ->where('user_id', Auth::user()->id)->get()->pluck('id');
        foreach ($added as $id) {
            $channel['videos'][$id]['added'] = true;
        }
        return $this->render($channel);
    }

    /**
     * Grab Video info from an ID
     *
     * @param String $id
     * @param \Illuminate\http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function video(String $id, Request $request)
    {
        return $this->render((new TubeStuff)->getVideos([$id]));
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Services\YouTubeService;

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

        $this->option('url', 'required|url');

        if (!$this->verify()) {
            return $this->error();
        }

        $result = (new YouTubeService)->parse($request->url);

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
      $channel = (new YouTubeService)->getChannel($id, $request->pageToken, Auth::user());
      return $this->render($channel);
    }
}

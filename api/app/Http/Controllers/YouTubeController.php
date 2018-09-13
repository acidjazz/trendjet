<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\YouTubeService;

class YouTubeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
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
}

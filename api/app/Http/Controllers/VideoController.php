<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->render($this->paginate(
            Video::where('user_id', Auth::user()->id)->withCount('boosts')
            , 9));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->option('ids', 'required|array');
        $this->verify();

        if (Video::whereIn('id', $request->ids)->count()) {
            return $this->error('video.exists');
        }

        Video::add(Auth::user(), $request->ids);
        return $this->success('video.add-success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        $this->authorize('view', $video);
        return $this->render($video->load(['logs','boosts']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Video $video)
    {
        $this->authorize('update', $video);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        $this->authorize('delete', $video);
        $video->delete();
        return $this->success('video.delete-success');
    }
}

<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Boost;
use App\Models\Video;
use App\Models\Activity;
use App\Http\Requests\StoreBoost;
use Illuminate\Http\Request;

class BoostController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth:api')->only(['index', 'store']);
        $this->middleware('apikey')->only('update');
        parent::__construct($request);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->render($this->paginate(Boost::where('user_id', Auth::user()->id)->with(['user','video']), 9));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->middleware('auth:api');

        $this->option('videos', 'required|array|exists:videos,id');
        $this->option('views', 'required|in:'.join(',', Boost::options));
        $this->verify();

        foreach ($request->videos as $video_id) {

            if (Video
                ::where('id', $video_id)
                ->where('user_id', Auth::user()->id)
                ->first() == null) {
                return $this->error('boost.unauthorized');
            }

            if (Boost
                ::where('user_id', Auth::user()->id)
                ->where('video_id', $video_id)
                ->where('status', '<>', Boost::COMPLETE)
                ->count() > 0) {
                return $this->error('boost.exists');
            }
        }

        if (($request->views * count($request->videos)) > Auth::user()->views) {
            return $this->error('boost.not-enough');
        }

        foreach ($request->videos as $video_id) {
            $boost = Boost::create([
                'user_id' => Auth::user()->id,
                'video_id' => $video_id,
                'views' => $request->views,
                'delivered' => 0,
                'status' => Boost::PENDING,
            ]);
            Activity::log('boost', Auth::user(), [
                'boost' => $boost,
                'video' => Video::find($video_id)->first(),
            ]);
        }

        Auth::user()->views -= ( $request->views * count($request->videos) );
        Auth::user()->save();


        if (count($request->videos) == 1) {
            $this->success('boost.created');
        } else {
            $this->success('boost.created_plural');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Boost  $boost
     * @return \Illuminate\Http\Response
     */
    public function show(Boost $boost)
    {
        return $this->render($boost->load(['user','video']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Boost  $boost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Boost $boost)
    {
        if ($boost->remaining  == 1) {
            $boost->status = Boost::COMPLETE;
            $boost->save();
        }
        $boost->increment('delivered');
        return $this->success('boost.updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Boost  $boost
     * @return \Illuminate\Http\Response
     */
    public function destroy(Boost $boost)
    {
        //
    }
}

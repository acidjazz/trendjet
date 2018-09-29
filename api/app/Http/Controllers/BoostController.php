<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Boost;
use App\Models\Video;
use App\Http\Requests\StoreBoost;
use Illuminate\Http\Request;

class BoostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->option('video_id', 'required|exists:videos,id');
        $this->option('views', 'required|in:'.join(',', Boost::options));
        $this->verify();

        if (Video
            ::where('id', $request->video_id)
            ->where('user_id', Auth::user()->id)
            ->count() < 1) {
            return $this->error('boost.unauthorized');
        }

        if (Boost
            ::where('user_id', Auth::user()->id)
            ->where('video_id', $request->video_id)
            ->where('status', '<>', Boost::COMPLETE)
            ->count() > 0) {
            return $this->error('boost.exists');
        }

        Boost::create([
            'user_id' => Auth::user()->id,
            'video_id' => $request->video_id,
            'views' => $request->views,
            'delivered' => 0,
            'status' => Boost::PENDING,
        ]);

        $this->success('boost.created');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Boost  $boost
     * @return \Illuminate\Http\Response
     */
    public function show(Boost $boost)
    {
        //
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
        //
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

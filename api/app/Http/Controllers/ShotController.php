<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Shot;
use Illuminate\Http\Request;

class ShotController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth:api')->only(['index']);
        $this->middleware('apikey')->only('store');

        parent::__construct($request);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->option('boost_id', 'required|exists:boosts,id');
        return $this->render($this->paginate(Shot::where('boost_id', $request->boost_id), 9));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->option('file', 'required|string');
        $this->option('duration', 'required|string');
        $this->verify();

        $ids = explode(':', basename($request->file));

        Shot::create([
            'video_id' => $ids[1],
            'boost_id' => $ids[2],
            'file' => basename($request->file),
            'duration' => Carbon\CarbonInterval::fromString($request->duration . ' seconds')->spec(),
        ]);

        return $this->success('shot.created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shot  $shot
     * @return \Illuminate\Http\Response
     */
    public function show(Shot $shot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shot  $shot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shot $shot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shot  $shot
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shot $shot)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Shot;
use Illuminate\Http\Request;

class ShotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->middleware('auth:api')->only(['index']);
        $this->middleware('apikey')->only('store');
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
        $this->verify();

        $ids = explode('-', basename($request->file));

        return $this->render(Shot::create([
            'video_id' => $ids[1],
            'boost_id' => $ids[2],
            'file' => basename($request->file),
        ]));
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

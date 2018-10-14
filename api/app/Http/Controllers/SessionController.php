<?php

namespace App\Http\Controllers;

use acidjazz\Humble\Models\Session;
use Illuminate\Http\Request;

use Auth;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->render(Auth::user()->sessions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  acidjazz\Humble\Models\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function show(Session $session)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  acidjazz\Humble\Models\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Session $session)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  acidjazz\Humble\Models\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function destroy(Session $session)
    {
        $session->delete();
        return $this->success('profile.revoke-success');
    }
}

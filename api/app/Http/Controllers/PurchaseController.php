<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Package;
use App\Models\Purchase;
use App\Models\Activity;
use Illuminate\Http\Request;

class PurchaseController extends Controller
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
        $this->option('package_id', 'required|exists:packages,id');
        $this->verify();

        $package = Package::find($request->package_id);

        if ($purchase = Purchase::create([
            'package_id' => $package->id,
            'user_id' => Auth::user()->id,
            'views' => $package->views,
        ])) {
            Activity::log('purchase', Auth::user(), $package);
            Auth::user()->views += $package->views;
            Auth::user()->save();
            $this->success('purchase.success', ['title' => $package->title]);
        }
        return $this->error();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}

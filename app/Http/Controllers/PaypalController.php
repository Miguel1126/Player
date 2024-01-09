<?php

namespace App\Http\Controllers;

use App\Models\Paypal;
use Illuminate\Http\Request;

class PaypalController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        try {
            $paypal = new Paypal();
            $paypal->username = $request->username;
            $paypal->paypal_id = $request->paypal_id;

            if ($paypal->save() >= 1) {
                return response()->json(['status' => 'OK', 'data' => $paypal], 201);
            }
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        try {
            $paypal = Paypal::get();
            return $paypal;
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $paypal = Paypal::findOrFail($request->id);
            $paypal->username = $request->username;
            $paypal->paypal_id = $request->paypal_id;

            if ($paypal->save() >= 1) {
                return response()->json(['status' => 'OK', 'data' => $paypal], 201);
            }
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $paypal = Paypal::FindORFail($request->id);
        $paypal->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
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
            $payment = new Payment();
            $payment->total = $request->total;
            $payment->date = $request->date;
            $payment->credit_card_id = $request->credit_card_id;
            $payment->paypal_id = $request->paypal_id;

            if ($payment->save() >= 1) {
                return response()->json(['status' => 'OK', 'data' => $payment], 201);
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
            $payment = Payment::get();
            return $payment;
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
            $payment = Payment::findOrFail($request->id);
            $payment->total = $request->total;
            $payment->date = $request->date;
            $payment->credit_card_id = $request->credit_card_id;
            $payment->paypal_id = $request->paypal_id;

            if ($payment->save() >= 1) {
                return response()->json(['status' => 'OK', 'data' => $payment], 201);
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
        $payment = Payment::FindORFail($request->id);
        $payment->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}

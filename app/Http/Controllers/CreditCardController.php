<?php

namespace App\Http\Controllers;

use App\Models\Credit_card;
use Illuminate\Http\Request;

class CreditCardController extends Controller
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
            $credit_card = new Credit_card();
            $credit_card->card_number = $request->card_number;
            $credit_card->month_expiration = $request->month_expiration;
            $credit_card->year_expiration = $request->year_expiration;
            $credit_card->security_code = $request->security_code;
            $credit_card->premium_id = $request->premium_id;

            if ($credit_card->save() >= 1) {
                return response()->json(['status' => 'OK', 'data' => $credit_card], 201);
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
            $credit_card = Credit_card::get();
            return $credit_card;
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
            $credit_card = Credit_card::findOrFail($request->id);
            $credit_card->card_number = $request->card_number;
            $credit_card->month_expiration = $request->month_expiration;
            $credit_card->year_expiration = $request->year_expiration;
            $credit_card->security_code = $request->security_code;
            $credit_card->premium_id = $request->premium_id;

            if ($credit_card->save() >= 1) {
                return response()->json(['status' => 'OK', 'data' => $credit_card], 201);
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
        $credit_card = Credit_card::FindORFail($request->id);
        $credit_card->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}

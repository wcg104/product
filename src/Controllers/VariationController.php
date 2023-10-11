<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Variation;  

use App\Http\Requests\variation\VariationRequest;
class VariationController extends Controller
{
    public function index()
    {
        $variation = Variation::paginate(10);
        $response = [
            'type' => 'success',
            'code' => 200,
            'message' => "List",
            'data' => $variation
        ];

        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VariationRequest $request)
    {

        try {
            $variation = Variation::create($request->all());
            $response = [
                'type' => 'success',
                'code' => 200,
                'message' => "Variation store successfully",
                'data' => $variation
            ];

            return response()->json($response, 200);

        } catch (\Throwable $th) {

            $response = [
                'type' => 'error',
                'code' => 500,
                'message' => $th->getMessage()
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Variation $variation)
    {
        $response = [
            'type' => 'success',
            'code' => 200,
            'message' => "Detailed Variation",
            'data' => $variation
        ];

        return response()->json($response,200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VariationRequest $request, Variation $variation)
    {

      try{
       
        $variation->update($request->all());
        return [
            'type' => 'success',
            'code' => 200,
            'message' => "Variation updated successfully",
            'data' => $variation
        ];
    }catch (\Throwable $th) {

        $response = [
            'type' => 'error',
            'code' => 500,
            'message' => $th->getMessage()
        ];
        return response()->json($response, 500);
    }
    
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Variation $variation)
    {
        try {
            $variation->delete();
            $response =  [
                "type" => "success",
                "code"=> 200,
                "message" => "Variation deleted successfully"
            ];
            return response()->json($response,200);
        } catch (\Throwable $th) {
            $response = [
                'type' => 'error',
                'code' => 500,
                'message' =>  $th->getMessage()
            ];
            return response()->json($response, 500);
        }        
    }
}


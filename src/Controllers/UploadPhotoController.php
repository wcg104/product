<?php

namespace App\Http\Controllers;

use App\Http\Requests\product\StoreProductMediaRequest;
use Illuminate\Http\Request;
use Validator;


class UploadPhotoController extends Controller
{
    //

    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($request->all(), [
                'product_image' => 'required|array',
                'product_image.*' => 'required|mimes:png,jpg,jpeg,mp4,webm'
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' =>  $validator->messages(), 'status' => 400], 200);
            }
            $files = [];
            $product_image = $input['product_image'];
            foreach ($product_image as $img) {
                $titleimage = mt_rand(3, 9) . time() . '.' . $img->extension();
                $path = $img->move(public_path('images/temp/'), $titleimage);
                $files[] = $titleimage;

            }

            return [
                'type' => 'success',
                'code' => 200,
                'message' => "photo created to temp folder",
                'data' => $files
            ]; ;
        } catch (\Throwable $th) {

            
            // Return the error response
            $response = [
                'type' => 'error',
                'code' => 500,
                'message' => $th->getMessage()
            ];
            return response()->json($response, 500);

 
        }


    }


    
}

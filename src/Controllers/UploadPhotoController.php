<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class UploadPhotoController extends Controller
{
    //

    public function store(Request $request)
    {
        $input = $request->all();

        $files = [];
        $product_image = $input['product_image'];
        foreach ($product_image as $key => $img) {
            $titleimage = mt_rand(3, 9) . time() . '.' . $img->extension();
            $path = $img->move('images/temp/', $titleimage);
            $files[] = $titleimage;
        }



        return $files;
        // $input['images'] = implode(",",$files);

    }


}
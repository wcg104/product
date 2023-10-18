<?php

namespace App\Http\Controllers;

use App\Http\Requests\product\StoreProductRequest;
use App\Http\Requests\product\StoreProdutItemRequest;
use App\Http\Requests\product\updateProductRequest;
use App\Models\Product;
use App\Models\ProductItem;
use App\Models\ProductItemSize;
use App\Models\ProductMedia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\File;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Product = Product::paginate(10);
        $response = [
            'type' => 'success',
            'code' => 200,
            'message' => "List of Products",
            'data' => $Product
        ];

        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     */

    
    public function store(Request $request)
    {
        $input = request()->all();
        try {

            DB::beginTransaction();
                
            $input = request()->all();
            $input['ordering'] = 1;
            $product = Product::create($input);
            // $input['product_id'] = $product->id;
          
            foreach ($input['product_item'] as $key => $product_item)
            {
 
                $productitem = ProductItem::create([
                    'color' => $product_item['color'],
                    'quantity' => $product_item['quantity'],
                    'price' => $product_item['price'],
                    'final_price' => $product_item['final_price'],
                    'is_available' => $product_item['is_available'],
                    'tags' => $product_item['tags'],
                    'product_id'=>$product->id,
                    'ordering'=>$key+1
                ]);


                    $this->moveImages($product_item['image'] ,$productitem);
                
                
                    $testArray2=[];
           
                foreach ($product_item['product_item_size'] as $item_key => $itemname) {
               
                $testArray2 =  [
                    'itemname' => $itemname['itemname'],
                    'itemquantity'=>$itemname['itemquantity'],
                    'product_item_id' =>$productitem->id
                ];
               
                $productsize = ProductItemSize::create($testArray2);
              
                }
            }
            DB::commit();

            $response = [
                'type' => 'success',
                'code' => 200,
                'message' => "Product store successfully",
                'data' => $product, $productitem->id
                
            ];   
            
            return response()->json($response, 200);
        }catch (\Throwable $th) {
            \Log::error($th);
            DB::rollBack();
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
    public function show(Product $product)
    {
        $response = [
            'type' => 'success',
            'code' => 200,
            'message' => "Detail Product",
            'data' => $product ,
        ];

        return response()->json($response, 200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {

        try {

            $input = request()->all();
            
            $product->update($input);

            $product_item_ids = ProductItem::where('product_id', $product->id)->get()->pluck('id')->toArray();
            
            $testArray = [];
            foreach ($input['product_item'] as $key => $product_item) {
                $testArray['product_id'] = $product->id;
                $testArray['color'] = $product_item['color'];
                $testArray['quantity'] = $product_item['quantity'];
                $testArray['price'] = $product_item['price'];
                $testArray['final_price'] = $product_item['final_price'];
                $testArray['is_available'] = $product_item['is_available'];
                $testArray['tags'] = $product_item['tags'];
                $testArray['ordering'] = $key+1;
               
                $productitem = ProductItem::updateOrCreate(['id'=>$product_item['id']],$testArray);
         
                $testArray2 = [];
            foreach ($product_item['product_item_size'] as $item_key => $itemname) {
                
                $testArray2 = [
                    'itemname' => $itemname['itemname'],
                    'itemquantity'=>$itemname['itemquantity'],
                    'product_item_id' =>$productitem->id
                ];

               
                ProductItemSize::updateOrCreate(['id'=>$itemname['id']],$testArray2);
            }

            $new_image = $product_item['image'];
            $old_image = ProductMedia::where('product_item_id',$productitem->id)->pluck('name')->toArray();
            
            
            
            $old_image_delete= array_diff($old_image , $new_image);
            $products = ProductMedia::where('product_item_id',$productitem->id)->whereIn('name',$old_image_delete)->delete();
            
            
            
            
            $update_new_image = array_diff($new_image,$old_image);
            $this->moveImages($update_new_image,$productitem);
            
            foreach ($new_image as $key => $update_new) {
                $update_ordering = ProductMedia::where('name',$update_new)->update(['ordering'=>$key+1]);
            }
            
            $this->deleteImage($old_image_delete);
        }

        return [
            'type' => 'success',
            'code' => 200,
            'message' => "Product updated successfully",
            'data' => $product
            ];
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
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {

        $product_items = ProductItem::where('product_id',$product->id)->get();
        foreach ($product_items as $key => $value) {
            $value->delete();
        }
    
            $product->delete();
            $response = [
                "type" => "success",
                "code" => 200,
                "message" => "Product deleted successfully"
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

    public function moveImages($images , $productitem ){

        foreach ($images as $key=> $img) {
           
          $imgtype = \Str::after($img, '.');
                if($imgtype == 'jpg' || $imgtype == 'jpeg' || $imgtype == 'png')
                {
                    $type = 'image';
                }
                else{
                    $type = 'video';
                }
            $sourcePath = public_path('images/temp/'.$img);
            
        
            $destinationPath = public_path('images/product_media/'.$img);
            $folderPath = public_path('images/product_media');  
                    if (!file_exists($folderPath)) {
                        mkdir($folderPath, 0755, true); // Create the folder if it doesn't exist.
                    }
            File::move($sourcePath, $destinationPath);
                    
                    $product_media =  ProductMedia::create([
                               'name'=>$img,
                               "image"=>$img,
                               "path"=>'images/product_media',
                               "ordering"=>$key+1,
                               'type'=>$type,
                               "product_item_id"=>$productitem->id,
       
                           ]);
       
                       }
                }

                public function deleteImage($image){
                    foreach ($image as $key => $value) {
                     
                        unlink(public_path('images/product_media/'.$value));
                    }
                }
    }



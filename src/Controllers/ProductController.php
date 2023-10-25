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
     * Display a listing of the product with its mutiple items and multiple images and sizes.
     */
    public function index()
    {
        $products = Product::with('items', 'items.images', 'items.sizes')->paginate(10);

        $response = [
            'type' => 'success',
            'code' => 200,
            'message' => "List of Products",
            'data' => $products
        ];

        return response()->json($response, 200);
    }

/**
 * This is the store method of the ProductController class.
 * It handles the creation of a new product.
 * The method receives a StoreProductRequest object as a parameter.
 * It starts a database transaction and creates a new product using the input data.
 * It also creates product items and their sizes, and associates them with the product.
 * If successful, it commits the transaction and returns a JSON response with the created product.
 * If an error occurs, it rolls back the transaction and returns an error JSON response.
 *  {
*     "name": "sparx" ,
*     "category_id": "1212",
*     "brand": "Zudio",
*     "is_active":1,
*     "product_type": "clothes",
*     "short_description": "clothing and accesories",
*     "product_item": [
*                    {
*                        
*                        "color":"Green",  
*                        "tags": "long tunic",
*                        "price": "10",
*                        "quantity":"10",
*                        "final_price" : "200",
*                        "is_available": "1",
*                        "product_item_size":[
*                            {
*                              
*                                "itemname":"sm",
*                                "itemquantity":"50"  
*                            },
*                             {
*                               
*                                "itemname":"xl",
*                                "itemquantity":"10"
*                            }
*                        ],
*                         "image" : [ "51697691383.jpg"]  
*                    },
*                    {
*                        
*                         "color":"olive green",
*                        "tags": "short tunic",
*                        "price": "10",
*                        "final_price" : "200",
*                          "quantity":"20",
*                        "is_available": "1",
*                      "product_item_size":[
*                            {
*                                "itemname":"s",
*                                "itemquantity":"50"
*                            },
*                             {
*                                "itemname":"m",
*                                "itemquantity":"10"
*                            }
*                        ],
*                        "image"  : ["71697691383.jpg"]
*                       
*                    }
*                   
*                ]
* }
 */

    public function store(StoreProductRequest $request)
    {
        // $input = request()->all();
        try {

            DB::beginTransaction();

            $input = request()->all();
            $input['ordering'] = 1;
            $product = Product::create($input);

            foreach ($input['product_item'] as $key => $product_item) {

                $input['product_item'][$key]['product_id'] = $product->id;
                $input['product_item'][$key]['ordering'] = $key + 1;
                $productitem = ProductItem::create(
                    $input['product_item'][$key]
                );

                $this->moveImages($product_item['image'], $productitem);


                $testArray2 = [];

                foreach ($product_item['product_item_size'] as $item_key => $itemname) {

                    $testArray2 = [
                        'itemname' => $itemname['itemname'],
                        'itemquantity' => $itemname['itemquantity'],
                        'product_item_id' => $productitem->id
                    ];

                    $productsize = ProductItemSize::create($testArray2);

                }
            }
            DB::commit();
            $product->load('items', 'items.images', 'items.sizes');

            $response = [
                'type' => 'success',
                'code' => 200,
                'message' => "Product store successfully",
                'data' => $product,
            ];

            return response()->json($response, 200);
        } catch (\Throwable $th) {
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
 * Retrieves the details of a product and returns it as a JSON response.
 *
 * @param  Product  $product  The product to retrieve details for
 * @return \Illuminate\Http\JsonResponse
 */
public function show(Product $product)
{
    $product->load('items', 'items.images', 'items.sizes');
    $response = [
        'type' => 'success',
        'code' => 200,
        'message' => "Detail Product",
        'data' => $product,
    ];
    return response()->json($response, 200);
}


/**
 * Summary: This code is responsible for updating a product and its associated items and images.
 * 
 * Description: This code receives a request to update a product and its associated items and images. It first retrieves the input data from the request and updates the product with the new data. Then, it iterates through each product item in the input data and updates or creates the corresponding product item and its associated sizes. It also handles the addition and deletion of product images. Finally, it returns a response indicating the success or failure of the update operation.
 * 
 * @param updateProductRequest $request: The request containing the updated product data.
 * @param Product $product: The product instance to be updated.
 * @return array: The response indicating the success or failure of the update operation.
 * 
 *  {
*    "name": "update title" ,
*    "category_id": "11221",
*    "brand": "Zudio",
*    "is_active":1,
*    "product_type": "clothes",
*    "short_description": "clothing and accesories",
*    "product_item": [
*                   {
*                       "id": "9a67aabe-274b-4574-a516-a97532d79a3f",
*                       "color":"Olive Green",
*                       "tags": "long tunic",
*                       "price": "10",
*                       "quantity":"10",
*                       "final_price" : "200",
*                       "is_available": "1",
*                       "product_item_size":[
*                           {
*                               "id": "",
*                               "itemname":"sm",
*                               "itemquantity":"50"
*                           },
*                            {
*                                "id":"",
*                               "itemname":"xl",
*                               "itemquantity":"10"
*                           }
*                       ],
*                       "image" : ["51697691383.jpg", "41697691827.jpg" , "71697691827.jpg" ]
*                      
*                   },
*                   {
*                       "id": "9a67aabe-27d2-4f5f-8ce3-6723349e0981",
*                        "color":"blue",
*                       "tags": "short tunic",
*                       "price": "10",
*                       "final_price" : "200",
*                        "quantity":"20",
*                       "is_available": "1",
*                     "product_item_size":[
*                           { 
*                               "id": "",
*                               "itemname":"s",
*                               "itemquantity":"50"
*                           },
*                            {
*                                "id": "",
*                               "itemname":"m",
*                               "itemquantity":"10"
*                           }
*                       ],
*                       
*                           "image":["71697691383.jpg"]  
*                          
*                           
*                      
*                   }
*               ]
* }
 */
public function update(updateProductRequest $request, Product $product)
{
    try {
        // Update the product with the new input data
        $input = request()->all();
        $product->update($input);

        // Iterate through each product item in the input data
        foreach ($input['product_item'] as $key => $product_item) {
            // Update or create the product item and its associated sizes
            $testArray['product_id'] = $product->id;
            $testArray['color'] = $product_item['color'];
            $testArray['quantity'] = $product_item['quantity'];
            $testArray['price'] = $product_item['price'];
            $testArray['final_price'] = $product_item['final_price'];
            $testArray['is_available'] = $product_item['is_available'];
            $testArray['tags'] = $product_item['tags'];
            $testArray['ordering'] = $key + 1;
            $productitem = ProductItem::updateOrCreate(['id' => $product_item['id']], $testArray);

            // Update or create the product item's sizes
            foreach ($product_item['product_item_size'] as $item_key => $itemname) {
                $testArray2 = [
                    'itemname' => $itemname['itemname'],
                    'itemquantity' => $itemname['itemquantity'],
                    'product_item_id' => $productitem->id
                ];
                ProductItemSize::updateOrCreate(['id' => $itemname['id']], $testArray2);
            }

            // Handle the addition and deletion of product images
            $new_image = $product_item['image'];
            $old_image = ProductMedia::where('product_item_id', $productitem->id)->pluck('name')->toArray();
            $old_image_delete = array_diff($old_image, $new_image);
            $update_new_image = array_diff($new_image, $old_image);
            $this->moveImages($update_new_image, $productitem);
            foreach ($new_image as $key => $update_new) {
                $update_ordering = ProductMedia::where('name', $update_new)->update(['ordering' => $key + 1]);
            }
            $products = ProductMedia::where('product_item_id', $productitem->id)->whereIn('name', $old_image_delete)->delete();
            $this->deleteImage($old_image_delete);
        }

        // Load the updated product with its associated items and images and sizes.
        $product->load('items', 'items.images', 'items.sizes');

        // Return the success response
        return [
            'type' => 'success',
            'code' => 200,
            'message' => "Product updated successfully",
            'data' => $product
        ];
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


 
/**
 * This code is written in PHP.
 *
 * Summary: This function deletes a product and its associated product items from the database.
 * It first retrieves all product items related to the given product, deletes them one by one,
 * and then deletes the product itself. It returns a JSON response indicating the success
 * or failure of the operation along with an appropriate message.
 */
public function destroy(Product $product)
{
    try {
        $product_items = ProductItem::where('product_id', $product->id)->get();
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

/**
 * This code moves images from a temporary folder to a product media folder.
 * It determines the type of each image (either 'image' or 'video') based on the file extension.
 * The code creates the destination folder if it doesn't exist and moves the image to the destination.
 * It then creates a ProductMedia record for each image, storing its name, path, ordering, type, and product item ID.
 *
 * @param array $images - An array of image file names
 * @param object $productitem - The product item object
 * @return void
 */
public function moveImages($images, $productitem)
{
    foreach ($images as $key => $img) {
        $imgtype = \Str::after($img, '.');
        if ($imgtype == 'jpg' || $imgtype == 'jpeg' || $imgtype == 'png') {
            $type = 'image';
        } else {
            $type = 'video';
        }
        $sourcePath = public_path('images/temp/' . $img);
        $destinationPath = public_path('images/product_media/' . $img);
        $folderPath = public_path('images/product_media');
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true); // Create the folder if it doesn't exist.
        }
        File::move($sourcePath, $destinationPath);
        $product_media = ProductMedia::create([
            'name' => $img,
            "path" => 'images/product_media',
            "ordering" => $key + 1,
            'type' => $type,
            "product_item_id" => $productitem->id,
        ]);
    }
}
/**
 * Deletes the specified images from the product media folder.
 *
 * @param array $image The array containing the names of the images to be deleted
 * @return void
 */
public function deleteImage($image)
{
    foreach ($image as $key => $value) {
        unlink(public_path('images/product_media/' . $value));
    }
}

/**
 * Update the ordering of products in the database.
 *
 * @param int $id The ID of the Product.
 * @param \Illuminate\Http\Request $request The HTTP request instance.
 * @return \Illuminate\Http\JsonResponse
 */
public function updateOrder($id, Request $request)
{
    $data = request()->all();
    foreach ($data['ordering'] as $key=>$product) {
        $productId = $product['id'];
        ProductItem::where('id',$productId)->update(['ordering'=>$product['order']]);
    }
    $response = [
        'type' => 'success',
        'code' => 200,
        'message' => 'Ordering updated successfully',
        'data' => $data // Include the ID in the response
    ];
    return response()->json($response, 200);
}

}       
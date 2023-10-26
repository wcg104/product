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
     * @bodyParam category_id uuid required The uuid of the category. Example: 9a67aabe-274b-4574-a516-a97532d79a3f
     * @bodyParam name string The name of the product.
     * @bodyParam brand string The name of the product's brand.
     * @bodyParam is_active boolean whether the product is active or not . Example: 1
     * @bodyParam product_type string required The type of the product.
     * @bodyParam short_description string required The short description of the product.
     * @bodyParam yet_another_param.name string required Subkey in the object param.
     * @bodyParam product_item array Some array params.
     * @bodyParam product_item.*.color string Subkey in the array param for the color of the product.
     * @bodyParam product_item.*.tags string Subkey in the array param for the tags of the product.
     * @bodyParam product_item.*.price integer Subkey in the array param for the color of the product.
     * @bodyParam product_item.*.quantity integer Subkey in the array param for the color of the product.
     * @bodyParam product_item.*.final_price double int Subkey in the array param for the final price of the product.
     * @bodyParam product_item.*.is_available boolean Subkey in the array param for the product item to check whether it is available or not.
     * @bodyParam product_item.*.product_item_size.*.itemname string Subkey in the array param for the size's name .
     * @bodyParam product_item.*.product_item_size.*.itemquantity integer Subkey in the array param for the size's quantity .
     * @bodyParam product_item.*.image array Subkey in the array param for the multiple image of the product.
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
          $this->show($product);

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
     * @bodyParam category_id uuid required The uuid of the category. Example: 9a67aabe-274b-4574-a516-a97532d79a3f
     * @bodyParam name string The name of the product.
     * @bodyParam brand string The name of the product's brand.
     * @bodyParam is_active boolean whether the product is active or not . Example: 1
     * @bodyParam product_type string required The type of the product.
     * @bodyParam short_description string required The short description of the product.
     * @bodyParam yet_another_param.name string required Subkey in the object param.
     * @bodyParam product_item array Some array params.
     * @bodyParam product_item.*.color string Subkey in the array param for the color of the product.
     * @bodyParam product_item.*.tags string Subkey in the array param for the tags of the product.
     * @bodyParam product_item.*.price integer Subkey in the array param for the color of the product.
     * @bodyParam product_item.*.quantity integer Subkey in the array param for the color of the product.
     * @bodyParam product_item.*.final_price double int Subkey in the array param for the final price of the product.
     * @bodyParam product_item.*.is_available boolean Subkey in the array param for the product item to check whether it is available or not.
     * @bodyParam product_item.*.product_item_size.*.itemname string Subkey in the array param for the size's name .
     * @bodyParam product_item.*.product_item_size.*.itemquantity integer Subkey in the array param for the size's quantity .
     * @bodyParam product_item.*.image array Subkey in the array param for the multiple image of the product.
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
          $this->show($product);

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
            foreach ($product_items as $product_item) {
                $product_item->delete();
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
     * This code moves files from a temporary folder to a product media folder.
     * It determines the type of each file (either 'image' or 'video') based on the file extension.
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
    public function deleteImage($images)
    {
        foreach ($images as $key => $value) {
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
    public function updateOrder($id)
    {
        $data = request()->all();
        foreach ($data['ordering'] as  $key => $product_item) {
            $product_item_id = $product_item['id'];
            ProductItem::where('id', $product_item_id)->update(['ordering' =>$key+1]);
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
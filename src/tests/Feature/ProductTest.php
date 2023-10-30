<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;


class ProductTest extends TestCase
{
    // use DatabaseTransactions;
    /**
     * A basic feature test example.
     *  one product with its multiple items and that multiple item have multiple images and sizes.
     *
     */
    
    //creating product with it multiple items , item sizes and images.
     public function test_for_product_creating()
     {
        $this->withoutExceptionHandling();
        $api = 'api/product';
         // Positive test case
         $product = [
            'name' => 'product A',
            'short_description' => 'this is the product testing ',
            'is_active' => '1',
            'brand' => 'gucci',
            'product_type'=>'shoes',
            'category_id'=>121212121,
            'product_item'=>[
                [
                    'color'=>'red',
                    'tags'=>'short tunics item 1',
                    'price'=>120,
                    'final_price'=>100,
                    'is_available'=>1,
                    'quantity'=>100,
                    'product_item_size'=>[
                        [
                            'itemname'=>'xl',
                            'itemquantity'=>10
                        ],
                        [
                            'itemname'=>'xxl',
                            'itemquantity'=>90
                        ]
                        ],
                    //add image to the temp folder and simply define name to these image array for adding it to the product_media folder.
                    'image'=>['71697690864.jpg']
                ]
            ]
         ]; 
         // Set request data here
         $response = $this->postJson($api,$product);
         $response->assertJson([
                      'type' => 'success',
                      'code' => 200,
                      'message' => 'Product store successfully'
                  ]);
 
        
     }

    
//     // fetching all the Products
    public function test_for_product_fetching_all()
    {
        // $this->test_for_product_creating();
        $api = 'api/product';


        $response = $this->get($api);
        
        $response
            ->assertJson([
                'type' => "success",
                'code' => 200,
                'message' => 'List of Products'
            ]);
    }

  // //updating product
    public function test_for_product_updating()
    {
        // $this->test_for_product_creating();
        $product = Product::where('deleted_at', null)->latest()->first();
        
        $api = 'api/product/' . $product->id;
        $product = [
            'name' => 'product UPDATE',
            'short_description' => 'this is the product testing ',
            'is_active' => '1',
            'brand' => 'gucci',
            'product_type'=>'shoes',
            'category_id'=>121212121,
            'product_item'=>[
                [
                    //add id of the no of items created at the time of storing for a single product. 
                    'id'=>"9a7df4a6-5037-45c3-acc9-eaab641dae47",
                    'color'=>'red',
                    'tags'=>'short tunics',
                    'price'=>120,
                    'final_price'=>100,
                    'is_available'=>1,
                    'quantity'=>100,
                    'product_item_size'=>[
                        [
                            //add id of the no of product item sizes created at the time of storing of product item. 
                            'id'=>"9a7df4b8-94c6-4fa0-b1fa-26cc4d9a2b99",
                            'itemname'=>'xl',
                            'itemquantity'=>10
                        ],
                        [
                            'id'=>"9a7df4b8-85df-4b5a-885e-734478357754",
                            'itemname'=>'xxl',
                            'itemquantity'=>90
                        ]
                        ],
                        //add image to the temp folder if want to update the older image.
                    'image'=>['71697690864.jpg']


                ]
            ]
         ];

        $response = $this->putJson($api, $product);
       

        $response->assertJson([
            'type' => "success",
            
        ]);
        $response
            ->assertJson([
                'type' => "success",
                'code' => 200,
                'message' => 'Product updated successfully'
            ]);
    }
   //deleting product
    public function test_for_product_deleting()
    {
        // $this->test_for_product_creating();
        $product = Product::where('deleted_at', null)->latest()->first();
        
        $api = 'api/product/' . $product->id;
    
        // $this->removeImages(explode(',',$productid->images),$productid->cover_image);
        $response = $this->delete($api);

        $response
            ->assertJson([
                'type' => "success",
                'code' => 200,
                'message' => 'Product deleted successfully'
            ]);
    }

    // //required validation test case
    public function test_for_product_validation_required()
    {

        $api = 'api/product';

        $product = [
            'name' => '',
            'short_description' => '',
            'is_active' => '',
            'brand' => '',
            'product_type'=>'',
            'category_id'=>'',
            'product_item'=>[
                [
                    'color'=>'',
                    'tags'=>'',
                    'price'=>'',
                    'final_price'=>'',
                    'is_available'=>'',
                    'quantity'=>'',
                    'product_item_size'=>[
                        [
                            'itemname'=>'',
                            'itemquantity'=>''
                        ],
                        [
                            'itemname'=>'',
                            'itemquantity'=>''
                        ]
                        ],
                    'image'=>['']


                ]
            ]
         ];

        $response = $this->postJson($api, $product);
        $response->assertJson([
                'type' => "error",
                'code' => 422,
                'message' => 'Server Validation Fail',
                "errors" => [
                    "name" => [
                        "Please Write a title"
                    ],
                    "category_id"=> [
                        "Please select 1 category"
                    ],
                    "brand"=> [
                        "Please write brand's name"
                    ],
                    "is_active"=> [
                        "Please add either active or inactive for the product"
                    ],
                    "product_type"=> [
                        "Please add Product Type"
                    ],
                    "short_description"=> [
                        "Please write some short description"
                    ],
                    "product_item.0.color"=> [
                        "The product_item.0.color field is required."
                    ],
                    "product_item.0.tags"=> [
                        "The product_item.0.tags field is required."
                    ],
                    "product_item.0.price"=> [
                        "The product_item.0.price field is required."
                    ],
                    "product_item.0.quantity"=> [
                        "The product_item.0.quantity field is required."
                    ],
                    "product_item.0.final_price"=> [
                        "The product_item.0.final_price field is required."
                    ],
                    "product_item.0.is_available"=> [
                        "The product_item.0.is_available field is required."
                    ],
                    "product_item.0.product_item_size.0.itemname"=> [
                        "The product_item.0.product_item_size.0.itemname field is required."
                    ],
                    "product_item.0.product_item_size.1.itemname"=> [
                        "The product_item.0.product_item_size.1.itemname field is required."
                    ],
                    "product_item.0.product_item_size.0.itemquantity"=> [
                        "The product_item.0.product_item_size.0.itemquantity field is required."
                    ],
                    "product_item.0.product_item_size.1.itemquantity"=> [
                        "The product_item.0.product_item_size.1.itemquantity field is required."
                    ],
                    "product_item.0.image.0"=> [
                        "The product_item.0.image.0 field is required."
                    ]
                ]
            
            ]);
    }




}

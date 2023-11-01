<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;


class ProductTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *  one product with its multiple items and that multiple item have multiple images and sizes.
     *
     */
    
    //creating product with it multiple items , item sizes and images.
    public function test_for_product_creating($imageDelete = "yes")
    {
        $this->withoutExceptionHandling();
        $api = 'api/product';
        // Positive test case
        $product = [
            'name' => 'product A',
            'short_description' => 'this is the product testing ',
            'is_active' => '1',
            'brand' => 'gucci',
            'product_type' => 'shoes',
            'category_id' => 121212121,
            'product_item' => [
                [
                    'color' => 'red',
                    'tags' => 'short tunics item 1',
                    'price' => 120,
                    'final_price' => 100,
                    'is_available' => 1,
                    'quantity' => 100,
                    'product_item_size' => [
                        [
                            'itemname' => 'xl',
                            'itemquantity' => 10
                        ],
                        [
                            'itemname' => 'xxl',
                            'itemquantity' => 90
                        ]
                    ],
                    //add image to the temp folder and simply define name to these image array for adding it to the product_media folder.
                    'image' => $this->image_type()
                ]
            ]
        ];
        // Set request data here
        $response = $this->postJson($api, $product);
        $response->assertJson([
            'type' => 'success',
            'code' => 200,
            'message' => 'Product store successfully'
        ]);

        if ($imageDelete == 'yes') {
            foreach ($response['data']['items'] as $product_item) {

                foreach ($product_item['images'] as $image) {
                    $imagePath = public_path() . '/images/product_media/' . $image['name'];
                    unlink($imagePath);
                }

            }
        }

    }


    // fetching all the Products
    public function test_for_product_fetching_all()
    {
        $this->test_for_product_creating("yes");
        $api = 'api/product';


        $response = $this->getJson($api);

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
        $this->test_for_product_creating('no');
        $product = Product::where('deleted_at', null)->latest()->first();
        // dd();
      
        $api = 'api/product/' . $product->id;
        $update_product = [
            'name' => 'product UPDATE',
            'short_description' => 'this is the product testing ',
            'is_active' => '1',
            'brand' => 'gucci',
            'product_type' => 'shoes',
            'category_id' => 121212121,
            'product_item' => [
                [
                    //add id of the no of items created at the time of storing for a single product. 
                    'id' => "",
                    'color' => 'red',
                    'tags' => 'short tunics',
                    'price' => 120,
                    'final_price' => 100,
                    'is_available' => 1,
                    'quantity' => 100,
                    'product_item_size' => [
                        [
                            //add id of the no of product item sizes created at the time of storing of product item. 
                            'id' => "",
                            'itemname' => 'xl',
                            'itemquantity' => 10
                        ],
                        [
                            'id' => "",
                            'itemname' => 'xxl',
                            'itemquantity' => 90
                        ]
                    ],
                    //add image to the temp folder if want to update the older image.
                    'image' => $this->image_type() 
                    
                    ]
                    
            ]
        ];

        $response = $this->putJson($api, $update_product);



        $response->assertJson([
            'type' => "success",

        ]);
        $response
            ->assertJson([
                'type' => "success",
                'code' => 200,
                'message' => 'Product updated successfully'
            ]);
            foreach ($response['data']['items'] as $product_item) {

                foreach ($product_item['images'] as $image) {
                    $imagePath = public_path() . '/images/product_media/' . $image['name'];
                    unlink($imagePath);
                }

            }
        
    }
    //    //deleting product
    public function test_for_product_deleting()
    {
        $this->test_for_product_creating('no');
        $product = Product::where('deleted_at', null)->latest()->first();

            $api = 'api/product/' . $product->id;

            $response = $this->delete($api);
            foreach ($product['items'] as  $product_item) {
                foreach ($product_item['images'] as $image) {
                    $imagePath = public_path() . '/images/product_media/' . $image['name'];

                    unlink($imagePath);
                }
            }
            
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



    public function image_type()
    {
        $api = 'api/photo/';

        // positive condition 
        $product_image = [
            'product_image' => [
                0 => UploadedFile::fake()->create('sample.mp4', '1000', 'mp4'),
                // 1 => UploadedFile::fake()->create("test.jpg", 100),
                // 2 => UploadedFile::fake()->create("test.jpg", 100),
            ],
        ];

        $response = $this->post($api, $product_image);
        return $response['data'];
    }



    public function test_for_image_type()
    {
        $api = 'api/photo/';

        // positive condition 
        $product_image = [
            'product_image' => [
                0 => UploadedFile::fake()->create('sample.mp4', '1000', 'mp4'),
                1 => UploadedFile::fake()->create("test.jpg", 100),

            ],
        ];

        $response = $this->post($api, $product_image);

        $response
            ->assertJson([
                'type' => "success",
                'code' => 200,
                'message' => 'photo created to temp folder',
                'data' => $response['data']
            ]);

        $this->removeImage($response['data']);
    }

    public function test_for_file_type()
    {
        $api = 'api/photo/';

        // negative condition 
        $product_image = [
            'product_image' => [
                0 => UploadedFile::fake()->create('sample.pdf', '1000', 'pdf'),

            ],
        ];

        $response = $this->post($api, $product_image);
        $response
            ->assertJson([
                'status' => 400,
            ]);
    }

    public function removeImage($images)
    {
        foreach ($images as $image) {
            $imagePath = public_path() . '/images/temp/' . $image;

            unlink($imagePath);
        }
    }
}

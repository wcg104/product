<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Carbon\Carbon;

class CategoryTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     */
    /**
     * A feature test to add a new review
     *
     * @return void
     */
    // category created 
    public function test_for_category_creating()
    {
        $api = 'api/category';
        $Category = [
            'title' => 'qr',
            'description' => 'Every gift from a friend is a wish for your happiness.',
            'parent_category' => 0,
            'is_parent' => 1,
        ];

        $response = $this->postJson($api, $Category);
        $response
            ->assertJson([
                'type' => "success",
                'code' => 200,
                'message' => 'Category store successfully'
            ]);
    }

    //fetching all the category
    public function test_for_category_fetching_all()
    {
        $this->test_for_category_creating();
        $api = 'api/category';


        $response = $this->getJson($api);
        
        $response
            ->assertJson([
                'type' => "success",
                'code' => 200,
                'message' => 'List of Category'
            ]);
    }
    
    //category updating with the id
    public function test_for_category_updating()
    {
       $this->test_for_category_creating();
        $category_id = Category::where('deleted_at',null)->latest()->first();
        
        $api = 'api/category/'.$category_id->id;
        $Category = [
            'title' => 'pq',
            'description' => 'update testing category .',
            'parent_category' => 0,
            'is_parent' => 1,
        ];

        $response = $this->put($api,$Category);
        
        $response
            ->assertJson([
                'type' => "success",
                'code' => 200,
                'message' => 'Category Updated Successfully'
            ]);
    }

    //category deleting with the id
    public function test_for_category_deleting()
    {
       $this->test_for_category_creating();
        $category_id = Category::where('deleted_at',null)->latest()->first();
        
        $api = 'api/category/'.$category_id->id;
        
        $response = $this->deleteJson($api);
       
        
        $response
            ->assertJson([
                'type' => "success",
                'code' => 200,
                'message' => 'Category deleted successfully'
            ]);
    }

    //checking with  all the required field  
    public function test_for_category_validation_required()
    {

        $api = 'api/category';

        $Category = [
            'title' => '',
            'description' => '',
            'parent_category' => '',
            'is_parent' => '',
           
        ];

        $response = $this->postJson($api, $Category);
        $response
            ->assertJson([
                'type' => "error",
                'code' => 422,
                'message' => 'Server Validation Fail',
                "errors" => [
                    "title" => [
                        "Please Write a title"
                    ],
                    "description" => [
                        "Please write some description"
                    ],
                    "is_parent" => [
                        "The is parent field is required."
                    ],
                    "parent_category" => [
                        "The parent category field is required."
                    ]
                ]
            ]);
    }

    //checking what if the maximum value to the given field is exceded
    public function test_for_category_maximum_value()
    {

        $api = 'api/category';

        $Category = [
            'title' => 'ELectronicssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss',
            'description' => 'Electronics is the study and use of electrical devices that operate at relatively low voltages by controlling the flow of electrons or other electrically charged particles in devices such as thermionic valves and semiconductors. The pure study of such devices is considered as a branch of physics.',
            'parent_category' => '0',
            'is_parent' => '1',
            
        ];

        $response = $this->postJson($api, $Category);
        $response
            ->assertJson([
                'type' => "error",
                'code' => 422,
                'message' => 'Server Validation Fail',
                "errors" => [
                    "title" => [
                        "Please make sure that the title should not be more than 50 characters"
                    ],
                    "description" => [
                        "The description field must not be greater than 255 characters."
                    ],
                    
                ]
            ]);
    }
}
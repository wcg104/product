<?php

namespace Tests\Feature;

use App\Models\Variation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Carbon\Carbon;

class VariationTest extends TestCase
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
    // variation created 
    public function test_for_variation_creating()
    {
        $api = 'api/variation';
        $Variation = [
            'title' => 'ROM',
            'type' => 'electronics.',
            'prefix' => 6,
            'postfix' => 'gb',
            'countable' => 1,
            'value' => 6,
            'category_id'=>1,
        ];

        $response = $this->postJson($api, $Variation);
        $response
            ->assertJson([
                'type' => "success",
                'code' => 200,
                'message' => 'Variation store successfully'
            ]);
    }

    // fetching all the Variation
    public function test_for_variation_fetching_all()
    {
        $this->test_for_variation_creating();
        $api = 'api/variation';


        $response = $this->getJson($api);
        
        $response
            ->assertJson([
                'type' => "success",
                'code' => 200,
                'message' => 'List of Variation'
            ]);
    }
    
    //variation updating with the id
    public function test_for_variation_updating()
    {
       $this->test_for_variation_creating();
        $variationId = Variation::where('deleted_at',null)->latest()->first();
        
        $api = 'api/variation/'.$variationId->id;
        $Variation = [
            'title' => 'RAM',
            'type' => 'electronics.',
            'prefix' => 8,
            'postfix' => 'gb',
            'countable' => 1,
            'value' => 6,
            'category_id'=>1,
        ];

        $response = $this->putJson($api,$Variation);
        
        $response
            ->assertJson([
                'type' => "success",
                'code' => 200,
                'message' => 'Variation updated successfully'
            ]);
    }

    // variation deleting with the id
    public function test_for_variation_deleting()
    {
       $this->test_for_variation_creating();
        $variation = Variation::where('deleted_at',null)->latest()->first();
        
        $api = 'api/variation/'.$variation->id;
        
        $response = $this->deleteJson($api);
       
        
        $response
            ->assertJson([
                'type' => "success",
                'code' => 200,
                'message' => 'Variation deleted successfully'
            ]);
    }

    //checking with  all the required field 
    public function test_for_variation_validation_required()
    {

        $api = 'api/variation';

        $Variation = [
            'title' => '',
            'type' => '',
            'prefix' => '',
            'postfix' => '',
            'countable' => '',
            'value' => '',
            'category_id'=>'',
        ];

        $response = $this->postJson($api, $Variation);
        $response
            ->assertJson([
                'type' => "error",
                'code' => 422,
                'message' => 'Server Validation Fail',
                "errors" => [
                    "title"=> [
                        "Please Write a title"
                    ],
                    "type" => [
                        "Please write type"
                    ],
                    "prefix"=> [
                        "The prefix field is required."
                    ],
                    "postfix"=> [
                        "The postfix field is required."
                    ],
                    "countable"=> [
                        "The countable field is required."
                    ],
                    "value"=> [
                        "The value field is required."
                    ]
                ]
            ]);
    }
}
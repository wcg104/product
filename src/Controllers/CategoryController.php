<?php

namespace App\Http\Controllers;

use App\Http\Requests\category\updateCategoryRequest;
use Illuminate\Http\Request;
use App\Http\Requests\category\StoreCategoryRequest;
use App\Models\Category;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::paginate(10);
        $response = [
            'type' => 'success',
            'code' => 200,
            'message' => "List of Category",
            'data' => $category
        ];

        return response()->json($response,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
         
            $request['slug'] =  \Str::slug($request['title']);
            $category =  Category::create($request->all());
        
        $response = [
            'type' => 'success',
            'code' => 200,
            'message' => "Category store successfully",
            'data' => $category
           
        ];

        return response()->json($response,200);
        
    }
        catch (\Throwable $th) {
           
            $response = [
                'type' => 'error',
                'code' => 500,
                'message' =>  $th->getMessage()
            ];
            return response()->json($response, 500);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $response = [
            'type' => 'success',
            'code' => 200,
            'message' => "Detail Category",
            'data' => $category
        ];

        return response()->json($response,200);

 

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(updateCategoryRequest $request, Category $category)
    {
        try {
            $request['slug'] =  \Str::slug($request['title']);
            $category->update($request->all());
            $response = [
                'type' => 'success',
                'code' => 200,
                'message' => "Category Updated Successfully",
                'data' => $category
            ];
    
            return response()->json($response,200);
            
        }
            catch (\Throwable $th) {
               
                $response = [
                    'type' => 'error',
                    'code' => 500,
                    'message' =>  $th->getMessage()
                ];
                return response()->json($response, 500);
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            $response =  [
                "type" => "success",
                "code"=> 200,
                "message" => "Category deleted successfully"
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

<?php

namespace App\Http\Requests\product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;


class updateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' =>'required',
            'short_description' => 'required|max:255',
            'long_description' => 'required',
            'in_stock' => 'required',
            'price'=> 'required',
            'discounted_price'=> 'required',
            'brand'=> 'required',
            'category'=> 'required',
            'images'=>'array',
            'images.*' => 'mimes:png,jpg,jpeg,webp|max:2048',
            'cover_image' => 'mimes:png,jpg,jpeg,webp|max:2048',
            'value' => 'required',
            'parent_product'=>'required',
            'main_category'=>'required',
            'variant'=>'required',
            'is_active'=>'boolean',
        ];
    }

    public function messages()
    {
     
       
        return [
            "name.required" => "Please Write a title",
            "short_description.required" => "Please write some short description",
            "long_description.requried" => "Please write some long description",
            "in_stock.requried" => "Please write number of stock ",
            "discounted_price.requried" => "Please write discounted price ",
            "brand.requried" => "Please write brand's name  ",
            "category.requried" => "Please select 1 category",
            "cover_image.requried" => "Please add one cover image ",
            "value.requried" => "Please add value ",
            "parent_product.requried" => "Please select parent product  ",
            "main_category.required" => "Please add 1 main Category",
            "variant.required"=>"please atleast 1 variant",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = [
            'type' => 'error',
            'code' => 422,
            'message' => "Server Validation Fail",
            'errors' =>$validator->errors()
        ];

        /**
         * Return response data in json formate
         */
        throw new HttpResponseException(response()->json($response, 422));
    }

}

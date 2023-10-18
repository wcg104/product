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
     public function validationData()
     {
         return json_decode($this->getContent(), true);
     }
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'category_id' => 'required',
            'brand' => 'required|max:100',
            'is_active' => 'required|boolean',
            'product_type' => 'required|',
            'short_description' => 'required|max:150',
            'product_item' => 'required|array',
            'product_item.*.color' => 'required',
            'product_item.*.tags' => 'required',
            'product_item.*.price' => 'required|numeric',
            'product_item.*.quantity' => 'required|numeric',
            'product_item.*.final_price' => 'required|numeric',
            'product_item.*.is_available' => 'required|boolean',
            'product_item.*.product_item_size' => 'required|array',
            'product_item.*.product_item_size.*.itemname' => 'required|max:100',
            'product_item.*.product_item_size.*.itemquantity' => 'required|numeric',
            'product_item.*.image' => 'required|array',
            'product_item.*.image.*' => 'required',

        ];
    }

    public function messages()
    {
     
       
        return [
            "name.required" => "Please Write a title",
            "short_description.required" => "Please write some short description",
            "brand.required" => "Please write brand's name",
            "category_id.required" => "Please select 1 category",
            "cover_image.required" => "Please add one cover image",
            "product_type.required" => "Please add Product Type",
            "is_active.required" => "Please add either active or inactive for the product",
            "color.required" => "Please enter the color for the product item",
            "price.required" => "Please enter the price for the product item",
            "final_price.required" => "Please enter the final price for the product item",
            "is_available.required" => "Please enter whether the product item is available or not",
            "quantity.required" => "Please enter the quantity for the product",
            "tags.required" => "Please enter the tag for the product",
            "image.required" => "Please select at least 1 image for the product",
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

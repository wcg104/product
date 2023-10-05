<?php

namespace App\Http\Requests\category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreCategoryRequest extends FormRequest
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
           
                'title' => 'required|max:50|unique:categories,title',
                'description' => 'required|max:255',
                'is_parent' => 'required',
                'parent_category' => 'required',
          
        ];
    }

    public function messages()
    {
        return [
            "title.required" => "Please Write a title",
            "title.max" => "Please make sure that the title should not be more than 50 characters",
            "description.required" => "Please write some description",
            "is_parent.requried" => "Please select whether category is parent or not",
            "parent_category.requried" => "Please select parent category",
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

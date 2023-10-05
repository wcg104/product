<?php

namespace App\Http\Requests\variation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class VariationRequest extends FormRequest
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
           
                'title' => 'required',
                'type'=>'required',
                'prefix' => 'required',
                'postfix' => 'required',
                'countable' => 'required',
                'value' => 'required',
        ];
    }

    public function messages()
    {
        return [
            "title.required" => "Please Write a title",
            "type.required" => "Please write type",
            "prefix.requried" => "Please write prefix for the variation",
            "postfix.requried" => "Please write postfix for the variation",
            "countable.requried" => "Please select wether these variation is countable or not ",
            "value.requried" => "Please enter some values  ",
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

<?php

namespace App\Http\Request;

use Urameshibr\Requests\FormRequest;   //this is from package from booststrap/app.php
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RequestManager extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    
    public function authorize()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    
    public function rules()
    {

        return [
             'fullname' => 'required|regex:/^[a-zA-Z\\s\\,\\.\\-]+$/',  
           ];
     }
    /**
     * Custom message for validation
     *
     * @return array
     */
    
    public function messages()
    {
        return [
             'fullname.required'  => 'Fullname could not be blank',
             'fullname.regex' => 'Please provide a valid name. Numbers or Special Characters are not allowed.',
        ];
    }

    protected function failedValidation(Validator $validator) 
    { 
        throw new HttpResponseException(response()->json(
            [
                "success"=>false,
                "error"=>$validator->errors(),
        ], 422));
     }

}
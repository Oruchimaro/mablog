<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check(); // will add authorization with policy
    }

    /**
     * Make a new Instance of Validator 
     *
     *
     */
    public function withValidator()
    {
        Validator::make($this->validationData(), [
            'title' => $this->rules()['title'],
            'body' => $this->rules()['body']
        ])->validateWithBag('postBag'); // error bag for frontend
    }

    /**
     * Get the data needed for validation
     */
    public function validationData()
    {
        return request()->only('title', 'body');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:3',
            'body' => 'required|min:5'
        ];
    }


    public function messages()
    {
        return [
            'title' => 'A title is Required',
            'title.min' => 'A title Must be at least 3 characters',
            'body.required' => 'A body is required',
            'body.min' => 'Min Lenght For a Post body is 1 word with 5 characters'
        ];
    }
}

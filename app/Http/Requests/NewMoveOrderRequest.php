<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewMoveOrderRequest extends FormRequest
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
            'begin_address' => 'required',
            'finish_address' => 'required',
            'phone' => 'required',
            'car_type' => 'required',
            'dateTime' => 'required',
        ];
    }

}

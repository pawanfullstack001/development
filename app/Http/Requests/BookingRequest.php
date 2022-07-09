<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BookingRequest extends FormRequest
{

    protected function failedValidation(Validator $validator)
    {
        $controllerObj = new Controller();
        throw new HttpResponseException($controllerObj->returnResponse(false, $validator->errors()->first()));
    }

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
            "driver_id"=>"required",
            "source"=>"required",
            "destination"=>"required",
            "longitude"=>"required",
            "destination_lat"=>"required",
            "destination_lng"=>"required",           
            "distance"=>"required",
            "duration"=>"required",
            "customer_name"=>"required",
            "customer_mob"=>"required",
            "country"=>"required",
            ];
    }

    public function messages()
    {
        return [
            "destination_lat.required"=>"Destination latitude is required.",
            "destination_lng.required"=>"Destination longitude is required.",
            "customer_mob.required"=>"Customer mobile field is required.",
        ];
    }
}

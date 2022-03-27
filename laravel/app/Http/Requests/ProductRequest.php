<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class ProductRequest extends FormRequest
{
    use RequestTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            "code" => "string|required|max:50|unique:products,code",
            "user_id" => "integer|required|exists:users,id",
            "name" => "string|required|max:100",     
     
            "stock" => "numeric|required|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/|min:0.00",
            "sold" => "numeric|required|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/|min:0.00",
            "price" => "numeric|required|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/|min:0.00"
        ];

        // VALIDASI IMAGES
        if(request()->hasFile("images")){
            $rules['images'] = "required";
            $rules["images.*"] = "required|image|mimes:jpeg,png,jpg|max:5025|dimensions:max_width=10000,max_height=10000";
        }

        if($this->method() == "PUT" || $this->method() == "put"){
            $rules["code"] = $rules["code"].",".$this->product->id;
        }

        return $rules;
    }
}

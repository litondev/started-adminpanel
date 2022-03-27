<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class CustomerRequest extends FormRequest
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
            "user_id" => "integer|required|exists:users,id",
            "code" => "string|required|max:50|unique:customers,code",
            "name" => "string|required|max:100",
            "address" => "nullable",
            "payment_type" => "nullable|in:CASH,CREDIT"
        ];

        if($this->method() == "PUT" || $this->method() == "put"){
            $rules["code"] = $rules["code"].",".$this->customer->id;
        }

        return $rules;
    }
}

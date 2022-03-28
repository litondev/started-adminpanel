<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class TransactionRequest extends FormRequest
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
            "code" => "string|required|max:50:unique:transactions,code",
            "customer_id" => "integer|required|exists:customers,id",
            "user_id" => "integer|required|exists:users,id",

            "total" => "required|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/|min:0.00",
            "quantity" => "required|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/|min:0.00",
            "grand_total" => "required|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/|min:0.00",

            "transaction_details" => "required|array",
            "transaction_details.*.product_id" => "required|integer|exists:products,id",
            "transaction_details.*.quantity" => "required|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/|min:0.00",
            "transaction_details.*.total" => "required|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/|min:0.00",
            "transaction_details.*.amount" => "required|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/|min:0.00",
        ];

        if($this->method() == "PUT" || $this->method() == "put"){
            $rules["code"] = $rules["code"].",".$this->po_supplier->id;
        }

        return $rules;
    }
}

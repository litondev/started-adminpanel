<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class UserRequest extends FormRequest
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
            "code" => "string|required|max:50|unique:users,code",
            "username" => "string|required|max:100|unique:users,username",
            "name" => "string|required|max:100",
            "email" => "string|required|max:50|unique:users,email",
            "password" => "nullable|string|min:8"        
        ];

        if($this->method() == "PUT" || $this->method() == "put"){
            $rules["code"] = $rules["code"].",".$this->user->id;
            $rules["username"] = $rules["username"].",".$this->user->id;
            $rules["email"] = $rules["email"].",".$this->user->id;

            if(!request()->filled("password")){
                unset($rules["password"]);
            }
        }

        return $rules;
    }
}

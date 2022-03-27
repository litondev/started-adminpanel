<?php

namespace App\ImportTemplates;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UserTemplateExport implements FromView
{   
    public function view(): View
    {            
    	$column = [
            // username 
            [
                "name" => "Username",
                "required" => true  
            ],
            // name
            [
                "name" => "Nama",
                "required" => true  
            ],
            // code
            [
                "name" => "Kode",
                "required" => true 
            ],
            // email
            [
                "name" => "Email",
                "required" => true
            ],
            // password
            [
                "name" => "Password",
                "required" => true
            ]
        ];

        $values = [
            // username
            [
                "value" => "Username"
            ],
            // name
            [
                "value" => "User"
            ],
            // code
            [
                "value" => "UR0001"
            ],            
            // email
            [
                "value" => "4yambakar@gmail.com"
            ],
            // password
            [
                "value" => "089787865656"
            ]
        ];

        return view('exports/templates/default',[
            "data" => $column,
            "values" => $values 
        ]);
    }   
}
<?php

namespace App\ImportTemplates;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProductTemplateExport implements FromView
{   
    public function view(): View
    {            
        $column = [
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
            // user_id
            [
                "name" => "User Kode",
                "required" => true
            ],            
            // price
            [
                "name" => "Harga",
                "required" => true 
            ],
            // stock
            [
                "name" => "Stok",
                "required" => true 
            ],
            // sold 
            [
                "name" => "Terjual",
                "required" => true
            ] 
        ];

        $values = [
            // name
            [
                "value" => "Product"
            ],
            // code
            [
                "value" => "PT0001"
            ],
            // user_id 
            [
                "value" => "UR0001",
            ],
            // price 
            [
                "value" => "100000.00"
            ],
            // stock
            [
                "value" => "10"
            ],
            // sold
            [
                "value" => "5"
            ]
        ];

        return view('exports/templates/default',[
            "data" => $column,
            "values" => $values 
        ]);
    }   
}
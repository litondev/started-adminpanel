<?php

namespace App\ImportTemplates;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CustomerTemplateExport implements FromView
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
            // address
            [
                "name" => "Alamat",
                "required" => false 
            ],
            // payment_type
            [
                "name" => "Type Pembayaran (CASH/CREDIT)",
                "required" => false
            ],            
        ];

        $values = [
            // name
            [
                "value" => "Watashi"
            ],
            // code
            [
                "value" => "CR0001"
            ],
            // user_id 
            [
                "value" => "UR0001",
            ],
            // address
            [
                "value" => "Jalan Suromadu"
            ],
            // payment_type
            [
                "value" => "CASH"
            ]            
        ];

        return view('exports/templates/default',[
            "data" => $column,
            "values" => $values 
        ]);
    }   
}
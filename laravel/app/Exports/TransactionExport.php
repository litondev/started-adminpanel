<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TransactionExport implements FromView
{   
    public $indexFilter;

    public function __construct($indexFilter){
        $this->indexFilter = $indexFilter;
    }

    public function view(): View
    {       
        return view('exports/transaction',[
            "data" => !request()->filled("all") 
              ? $this->indexFilter->getCollection() 
              : $this->indexFilter
        ]);
    }   
}

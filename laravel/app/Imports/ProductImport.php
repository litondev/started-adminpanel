<?php

namespace App\Imports;

use App\Models\{
    Product,
    User
};
use Maatwebsite\Excel\Concerns\{
    WithStartRow,
    OnEachRow,    
    WithLimit,
    SkipsEmptyRows,
};
use Maatwebsite\Excel\Row;
use App\Traits\ImportTrait;

class ProductImport implements OnEachRow,WithLimit,SkipsEmptyRows,WithStartRow
{    
    use ImportTrait;
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function onRow(Row $row)
    {                
        $rawRow = $row;       
        $row = $row->toArray();                
             
        // \Log::info("Your in validation");
        $validator = \Validator::make($row,$this->rules(),$this->validations());

        if ($validator->fails()) {
            // \Log::info("Your fail validator");                        

            $this->faileds[] = [
                "row" => $rawRow->getIndex(),
                "code" => $row[1] ?? '-',
                "errors" => $validator->errors(),
                "type" => "validation"
            ];

            return Null;
        }

        // \Log::info("Your in query");
        try{         
            \DB::beginTransaction();       
                             
            // CHECK USER CODE IS EXISTS
            $user = User::query()
                ->select("id")
                ->where("code",$row[2])
                ->first();

            throw_if(
                !$user,
                new \Exception("User tidak ditemukan",422)
            );
        
            // GET AND SET    
            Product::updateOrCreate([
                "code" => $row[1],            
            ],[     
                "name" => $row[0],
                "code" => $row[1],
                "user_id" => $user->id,
                "price" => $row[3],
                "stock" => $row[4],
                "sold" => $row[5]
            ]);

            // SET SUCCESS CREATE
            $this->success[] = [
                "row" => $rawRow->getIndex(),            
                "code" => $row[1]
            ];       

            \DB::commit();     
        }catch(\Exception $e){
            \DB::rollback();

            // LOG ERROR
            if($e->getCode() != 422){
                \Log::channel("coex")->info($e->getMessage());
            }

            // SET FAILED CREATE
            $this->faileds[] = [
                "row" => $rawRow->getIndex(),
                "code" => $row[1],            
                "errors" => $e->getCode() != 422 
                    ? "Terjadi kesalahan ketika memasukan ke database" 
                    : $e->getMessage(),
                "type" => "query"
            ];

            return Null;
        }
    }

    public function rules(): array
    {              
        return [
            // name
            '0' => 'string|required|max:100',

            // code
            '1' => 'string|required|max:50',

            // user_id
            '2' => 'string|required',
       
            // price 
            '3' => 'numeric|required|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/|min:0.00',

            // stock
            '4' => 'numeric|required|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/|min:0.00',

            // sold
            '5' => 'numeric|required|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/|min:0.00'
        ];
    }

    public function validations(): array
    {
        return [
            // name
            '0.required' => "Nama harus diisi",          
            '0.max'      => 'Nama terlalu panjang max 100 huruf',

            // code 
            '1.required' => "Kode harus diisi",
            '1.max'      => 'Kode terlalu panjang max 25 huruf',
            
            // user_id 
            '2.required' => "Kode user harus diisi",

            // price 
            '3.numeric'  => "Harga tidak valid",
            "3.required" => "Harga harus diisi",
            "3.regex" => "Harga tidak valid",
            "3.min" => "Harga harus lebih dari 0",

            // stock
            '4.numeric'  => "Stok tidak valid",
            "4.required" => "Stok harus diisi",
            "4.regex" => "Stok tidak valid",
            "4.min" => "Stok harus lebih dari 0",

            // sold
            '5.numeric'  => "Terjual tidak valid",
            "5.required" => "Terjual harus diisi",
            "5.regex" => "Terjual tidak valid",
            "5.min" => "Terjual harus lebih dari 0",
        ];
    }
}
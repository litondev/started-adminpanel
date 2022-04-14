<?php

namespace App\Imports;

use App\Models\{
    Customer,
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

class CustomerImport implements OnEachRow,WithLimit,SkipsEmptyRows,WithStartRow
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
            Customer::updateOrCreate([
                "code" => $row[1],            
            ],[     
                "name" => $row[0],
                "code" => $row[1],
                "user_id" => $user->id,
                "address" => $row[3],
                "payment_type" => $row[4] ?? "CASH"
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
       
            // address
            '3' => 'nullable',

            // payment_type
            '4' => 'nullable|in:CASH,CREDIT',
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

            // address            

            // payment_type
            '4.in' => 'Type Pembayaran harus CASH/CREDIT',    
        ];
    }
}
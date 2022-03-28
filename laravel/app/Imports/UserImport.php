<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\{
    WithStartRow,
    OnEachRow,    
    WithLimit,
    SkipsEmptyRows,
};
use Maatwebsite\Excel\Row;
use App\Traits\ImportTrait;

class UserImport implements OnEachRow,WithLimit,SkipsEmptyRows,WithStartRow
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
                     
        $validator = \Validator::make($row,$this->rules(),$this->validations());

        if ($validator->fails()) {            
            $this->faileds[] = [
                "row" => $rawRow->getIndex(),
                "code" => $row[2] ?? '-',
                "errors" => $validator->errors(),
                "type" => "validation"
            ];

            return Null;
        }
    
        try{        
            \DB::beginTransaction();       

            // CHECK EMAIL IS EXISTS 
            throw_if(
                User::query()
                    ->select("id")
                    ->where("email",$row[3])
                    ->where("code","!=",$row[2])
                    ->first(),
                new \Exception("Email telah terpakai",422)
            );

            // CHECK USERNAME IS EXISTS
            throw_if(
                User::query()
                    ->select("id")
                    ->where("username",$row[0])
                    ->where("code","!=",$row[2])
                    ->first(),
                new \Exception("Username telah terpakai",422)
            );

            // GET AND SET 
            User::updateOrCreate([
                "code" => $row[2],            
            ],[     
                "username" => $row[0],
                "name" => $row[1],
                "code" => $row[2],    
                "email" => $row[3],
                "password" => \Hash::make($row[4])
            ]);

            // SET SUCCESS CREATE
            $this->success[] = [
                "row" => $rawRow->getIndex(),            
                "code" => $row[2]
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
                "code" => $row[2],            
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
            // username
            '0' => 'string|required|max:100',

            // name
            '1' => 'string|required|max:100',

            // code
            '2' => 'string|required|max:50',
            
            // email
            '3' => 'string|required|email|max:50',

            // password 
            '4' => 'string|required|min:8'
        ];
    }

    public function validations(): array
    {
        return [
            // username
            '0.required' => "Username harus diisi",          
            '0.max'      => 'Username terlalu panjang max 100 huruf',

            // name
            '1.required' => "Nama harus diisi",
            '1.max'      => "Name terlalu panjang max 100 huruf",

            // code 
            '2.required' => "Kode harus diisi",
            '2.max'      => 'Kode terlalu panjang max 50 huruf',
        
            // email
            '3.required' => 'Email harus diisi',
            '3.max' => 'Email terlalu panjang max 50 huruf',
            '3.email' => "Email tidak valid",

            // password
            '4.required' => 'Password harus diisi',
            '4.min' => 'Password terlalu pendek min 8 karakter'
        ];
    }
}
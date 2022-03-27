<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    User,
    Customer,
    Product
};

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<50;$i++){
            User::create([
                "code" => "UR.".$i."."."202203".".".$i,
                "username" => "user-".($i + 1),
                "name" => "user-".($i + 1),
                "password" => \Hash::make("12345678"),
                "email" => "user-".$i."@gmail.com"
            ]);
        }

        for($i=0;$i<50;$i++){
            Customer::create([
                "code" => "CR.".$i."."."202203".".".$i,
                "name" => "customer-".($i + 1),            
                "user_id" => 1
            ]);
        }

        for($i=0;$i<30;$i++){
            Product::create([
                "code" => "PT.".$i."."."202203".".".$i,
                "name" => "product-".($i + 1),            
                "user_id" => 1
            ]);
        }
    }
}
